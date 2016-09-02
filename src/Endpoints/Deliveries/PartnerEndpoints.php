<?php
namespace MPAPI\Endpoints\Deliveries;

use MPAPI\Endpoints\AbstractEndpoints;
use MPAPI\Services\Client;
use MPAPI\Services\AbstractService;
use MPAPI\Lib\DataCollector;
use MPAPI\Entity\PartnerDelivery;
use MPAPI\Entity\AbstractDelivery;
use function GuzzleHttp\json_decode;

/**
 *
 * @author Martin Hrdlicka <martin.hrdlicka@mall.cz>
 */
class PartnerEndpoints extends AbstractEndpoints
{

	/**
	 *
	 * @var string
	 */
	const ENDPOINT_PATH = 'deliveries/partner%s%s';

	/**
	 *
	 * @var Client
	 */
	protected $client;

	/**
	 *
	 * @var AbstractService
	 */
	protected $service;

	/**
	 *
	 * @param Client $client
	 * @param AbstractService $service
	 */
	public function __construct(Client $client, AbstractService $service)
	{
		parent::__construct($client);
		$this->service = $service;
	}

	/**
	 * Get all the endpoints that use GET
	 *
	 * @param $code null
	 * @return null|array|PartnerDelivery
	 */
	public function get($code = null)
	{
		$retval = null;
		if (empty($code)) {
			$retval = $this->getList();
		} else {
			$retval = $this->getDetail($code);
		}
		return $retval;
	}

	/**
	 * Get all the endpoints that use POST
	 *
	 * @param AbstractDelivery $deliveryEntity
	 * @return PartnerPostEndpoints
	 */
	public function post(AbstractDelivery $deliveryEntity = null)
	{
		$entitiesQueue = $this->service->getEntities();

		if (!empty($entitiesQueue)) {
			foreach ($entitiesQueue as $entity) {
				if ($entity instanceof AbstractDelivery) {
					$this->postDelivery($entity);
				}
			}
		} else {
			$this->postDelivery($deliveryEntity);
		}

		return empty($this->getErrors());
	}

	/**
	 * Get all the endpoints that use PUT
	 *
	 * @return PartnerPutEndpoints
	 */
	public function put()
	{
		return new PartnerPutEndpoints($this->client, $this->service);
	}

	/**
	 * Get all the endpoints that use DELETE
	 *
	 * @return PartnerDeleteEndpoints
	 */
	public function delete()
	{
		return new PartnerDeleteEndpoints($this->client, $this->service);
	}

	/**
	 * Get list of partner deliveries
	 *
	 * @return array
	 */
	private function getList()
	{
		$response = $this->client->sendRequest(sprintf(self::ENDPOINT_PATH, null, null), Client::METHOD_GET);
		$dataCollector = new DataCollector($this->client, $response);
		return $dataCollector->getData();
	}

	/**
	 * Get detail of partner delivery
	 *
	 * @param string $code
	 * @return null|\MPAPI\Endpoints\Deliveries\PartnerDelivery
	 */
	private function getDetail($code)
	{
		$retval = null;
		$response = $this->client->sendRequest(sprintf(self::ENDPOINT_PATH, '/', $code), Client::METHOD_GET);
		$responseData = json_decode($response->getBody(), true);
		if (isset($responseData['data']) && !empty($responseData['data'])) {
			$retval = new PartnerDelivery($responseData['data']);
		}
		return $retval;
	}

	/**
	 * Post delivery to API
	 *
	 * @param AbstractDelivery $data
	 * @return boolean
	 */
	private function postDelivery(AbstractDelivery $entity)
	{
		$response = $this->client->sendRequest(sprintf(self::ENDPOINT_PATH, null, null), Client::METHOD_POST, $entity->getData());
		$this->addError($entity->getCode(), json_decode($response->getBody(), true));

		return true;
	}
}
