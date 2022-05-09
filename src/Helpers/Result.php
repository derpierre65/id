<?php

namespace derpierre65\Id\Helpers;

use Exception;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;
use stdClass;

class Result
{
	public bool $success = false;

	/**
	 * Query result data.
	 */
	public null|array|stdClass $data;

	/**
	 * Status Code.
	 */
	public int $status = 0;

	/**
	 * @param null|ResponseInterface $response Original Guzzle HTTP Response.
	 * @param Exception|null $exception        Guzzle exception, if present.
	 */
	public function __construct(public ?ResponseInterface $response, public ?Exception $exception = null)
	{
		$this->success = null === $exception;
		$this->status = $response ? $response->getStatusCode() : 500;

		$responseInterface = null;
		if ( $exception instanceof RequestException ) {
			$responseInterface = $exception->getResponse();
		}
		elseif ( $response ) {
			$responseInterface = $response;
		}

		$contents = null;
		if ( $responseInterface && $responseInterface->getBody() ) {
			$contents = $responseInterface->getBody()->getContents();
		}

		$jsonResponse = $contents ? @json_decode($contents, false) : null;
		if ( null !== $jsonResponse ) {
			$this->data = $jsonResponse;
		}
	}

	/**
	 * Returns whether the query was successful.
	 */
	public function success() : bool
	{
		return $this->success;
	}

	/**
	 * Get the response data, also available as public attribute.
	 */
	public function data() : array|stdClass|null
	{
		return $this->data;
	}

	/**
	 * Returns the last HTTP or API error.
	 *
	 * @return string|null Error message or null if no error appeared
	 */
	public function error() : ?string
	{
		if ( $this->success() ) {
			return null;
		}

		if ( null === $this->exception ) {
			return 'API Unavailable';
		}

		$data = $this->data();

		return (string) ($data->message ?? $data->error->message ?? $this->exception->getMessage());
	}

	/**
	 * Returns guzzle http response.
	 */
	public function response() : ?ResponseInterface
	{
		return $this->response;
	}
}
