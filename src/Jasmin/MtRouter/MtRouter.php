<?php
/**
 * Created by pawel (pawel.samysev@gmail.com)
 * Date: 29.06.16
 */

namespace JasminWeb\Jasmin\MtRouter;

use JasminWeb\Exception\MtRouterException;
use JasminWeb\Jasmin\BaseObject;
use JasminWeb\Jasmin\TelnetConnector;

class MtRouter extends BaseObject {
  /**
   * A route without a filter, this one can only set with the lowest order to be a default/fallback route
   */
  const DefaultRoute = 'DefaultRoute';

  /**
   * A basic route with Filters and one Connector
   */
  const StaticMTRoute = 'StaticMTRoute';

  /**
   * A route with Filters and many Connectors, will return a random Connector if its Filters are matching, can be used as a load balancer route
   */
  const RandomRoundrobinMTRoute = 'RandomRoundrobinMTRoute';

  protected $command = 'mtrouter';

  protected $requiredAttributes = ['order'];

  public function getId() {
    return $this->attributes['order'];
  }

  public function setId($id) {
    $this->attributes['order'] = $id;
  }

  public function getAll() {
    $fetch_routers = parent::getAll();

    // Explode jcli command output to fetch routers
    $exploded = explode("#", $fetch_routers);

    // Unset first and second elements that include unwanted results from the command group -l
    unset($exploded[0]);
    unset($exploded[1]);

    $routers = [];
    foreach ($exploded as $expl) {
      $router = trim($expl);

      //fetch string before the "Total MT Routes:" lectic
      $ff = strstr($expl, 'Total MT Routes:', true);
      if (!empty($ff)) {
        $router = trim($ff);
      }

      //Get the filters
      preg_match_all('~<(.*?)>~', $router, $MTfilters);

      //Fix and clean blank spaces
      $temp_MT = explode(" ", $router);
      $temp_MT = array_filter($temp_MT);
      $fixed_MT = array();
      foreach ($temp_MT as $temp) {
        array_push($fixed_MT, $temp);
      }

      $routers[] = [
        'order' => $fixed_MT[0],
        'type' => $fixed_MT[1],
        'rate' => intval($fixed_MT[2]),
        'connector' => $fixed_MT[3],
        'filters' => $MTfilters[1],
      ];
    }

    return $routers;
  }

  /**
   * Check is at db exist group with that gid
   * @param $order
   * @return bool
   */
  public function checkExist($order) {
    foreach ($this->getAll() as $router) {
      if ($router['order'] == $order) {
        return true;
      }
    }
    return false;
  }

  /**
   * Create morouter by morouter type
   * @param $type
   * @param TelnetConnector $connection
   * @return DefaultRoute|StaticMTRoute|RandomRoundrobinMTRoute
   * @throws MtRouterException
   */
  public static function getRouter($type, TelnetConnector $connection) {
    switch ($type) {
    case (self::DefaultRoute): {
        return new DefaultRoute($connection);
      }
    case (self::StaticMTRoute): {
        return new StaticMTRoute($connection);
      }
    case (self::RandomRoundrobinMTRoute): {
        return new RandomRoundrobinMTRoute($connection);
      }
    default:
      throw new MtRouterException('Try create filter with unknown type');
    }
  }

  public function add() {
    throw new \Exception('Not realise function for child class');
  }

  public function save() {
    if (get_class($this) == 'MoRouter') {
      throw new \Exception('Not realise function for child class');
    }
    return parent::save();
  }

  public function setFilters($filters) {
    if (!is_array($filters)) {
      throw new MtRouterException('Filters should be array');
    }
    $this->attributes['filters'] = implode(';', $filters);
    return $this;
  }

  public function getFilters() {
    if (!isset($this->attributes['filters'])) {
      return [];
    }
    return explode(';', $this->attributes['filters']);
  }
}
