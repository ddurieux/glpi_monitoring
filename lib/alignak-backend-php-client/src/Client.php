<?php

require dirname(__DIR__).'/vendor/autoload.php';

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Alignak_Backend_Client {

    private $connected = false;
    private $authenticated = false;
    private $processes = 1;
    private $BACKEND_PAGINATION_LIMIT = 50;
    private $BACKEND_PAGINATION_DEFAULT = 25;
    private $url_endpoint_root = '';
    public $client = NULL;
    public $token = NULL;
    public $logger_debug = FALSE;

    /**
     * Initiate configuration
     *
     * @param type $endpoint root endpoint (API URL)
     * @param type $processes Number of processes used by GET
     */
    public function __construct($endpoint, $processes=1) {
        $this->processes = $processes;
        if (substr($endpoint, -1) == '/') {
            $this->url_endpoint_root = substr($endpoint, 0, -1);
        } else {
            $this->url_endpoint_root = $endpoint;
        }
        $this->client = new Client([
            'base_uri' => $this->url_endpoint_root,
            'timeout'  => 2.0,
        ]);
    }

    /**
     * Log into the backend and get the token
     *
     *   generate parameter may have following values:
     *   - enabled: require current token (default)
     *   - force: force new token generation
     *   - disabled
     *
     *   if login is:
     *   - accepted, returns True
     *   - refused, returns False
     *
     *   In case of any error, raises a BackendException
     *
     *
     * @param type $username login name
     * @param type $password password
     * @param type $generate Can have these values: enabled | force | disabled
     */
    function login($username, $password, $generate='enabled') {
        if ($this->logger_debug) {
            error_log("request backend authentication for: ".$username.", generate: ".$generate);
        }

        if (empty($username) OR empty($password)) {
            throw new Exception('Missing mandatory parameters', 1001);
        }

        $this->authenticated = false;
        $this->token = NULL;

        $params = array('username' => $username, 'password' => $password);
        if ($generate == 'force') {
            $params['action'] = 'generate';
        }

        $response = $this->client->request('POST', '/login', [
            'json'        => $params,
            'http_errors' => true
        ]);
        if ($response->getStatusCode() == 401) {
            if ($this->logger_debug) {
                error_log("authentication refused: ".$response->getBody()->getContents());
            }
            return FALSE;
        }

        $body = $response->getBody();
        $resp = json_decode($body->getContents(), true);
        if ($this->logger_debug) {
            error_log("authentication response: ".$body->getContents());
        }

        if (isset($resp['_status'])) {
            // Considering an information is returned if a _status field is present ...
            if ($this->logger_debug) {
                error_log("backend status: ".$resp['_status']);
            }
        }

        if (isset($resp['_error'])) {
            // Considering a problem occured is an _error field is present ...
            $error = $resp['_error'];
            if ($this->logger_debug) {
                error_log("authentication, error: ".$error['code'].", ".$error['message']);
            }
            throw new Exception($error['message'], $error['code']);
        } else {
            if (isset($resp['token'])) {
                $this->token = $resp['token'];
                $this->authenticated = true;
                if ($this->logger_debug) {
                    error_log("user authenticated: ".$username);
                }
                return TRUE;
            } else if ($generate == 'force') {
                if ($this->logger_debug) {
                    error_log("Token generation required but none provided.");
                }
                throw new Exception("Token not provided", 1004);
            } else if ($generate == 'disabled') {
                if ($this->logger_debug) {
                    error_log("Token disabled ... to be implemented!");
                }
                return FALSE;
            } else if ($generate == 'enabled') {
                if ($this->logger_debug) {
                    error_log("Token enabled, but none provided, require new token generation");
                }
                return $this->login($username, $password, 'force');
            }
            return FALSE;
        }
    }

    function logout() {
        // TODO
        $this->authenticated = false;
        $this->token = NULL;

        return true;
    }

    /**
     *  Connect to alignak backend and retrieve all available child endpoints of root

     *  If connection is successfull, returns a list of all the resources available in the backend:
     *  Each resource is identified with its title and provides its endpoint relative to backend
     *  root endpoint.
     *      [
     *          {u'href': u'loghost', u'title': u'loghost'},
     *          {u'href': u'escalation', u'title': u'escalation'},
     *          ...
     *      ]
     *
     *  If an error occurs a BackendException is raised.
     *
     *  If an exception occurs, it is raised to caller.
     *
     */
    function get_domains() {
        if (is_null($this->token)) {
            if ($this->logger_debug) {
                error_log("Authentication is required for getting an object.");
            }
            throw new Exception("Access denied, please login before trying to get", 1001);
        }

        if ($this->logger_debug) {
            error_log("trying to get domains from backend: ".$this->url_endpoint_root);
        }

        $resp = $this->get('');
        if ($this->logger_debug) {
            error_log("received domains data: ".$resp);
        }
        if (isset($resp["_links"])) {
            $_links = $resp["_links"];
            if (isset($_links["child"])) {
                return $_links["child"];
            }
        }
        return array();
    }

    /**
     *  Get items or item in alignak backend
     *
     *  If an error occurs, a BackendException is raised.
     *
     * @param type $endpoint endpoint (API URL) relative from root endpoint
     * @param type $params list of parameters for the backend API
     */
    function get($endpoint, $params=array()) {
        if (is_null($this->token)) {
            if ($this->logger_debug) {
                error_log("Authentication is required for getting an object.");
            }
            throw new Exception("Access denied, please login before trying to get", 1001);
        }
        try {
            if ($this->logger_debug) {
                error_log("get, endpoint: ".$endpoint.", parameters: ".print_r($params, true));
            }

            $response = $this->client->request('GET', '/'.$endpoint, [
                'query'       => $params,
                'auth'        => [$this->token, ''],
                'http_errors' => true
            ]);
        } catch (ClientException $e) {
            if ($e->getStatusCode() == 404) {
                throw new Exception('Not found', 404);
            }
            if ($this->logger_debug) {
                error_log("Backend HTTP error, error: ".$e->getResponse());
            }
            throw new Exception("Backend HTTPError: ".$e->getResponse(), 1003);
        }
        $body = $response->getBody();
        $resp = json_decode($body->getContents(), true);
        if (isset($resp['_status'])) {
            // Considering an information is returned if a _status field is present ...
            if ($this->logger_debug) {
                error_log("backend status: ".$resp['_status']);
            }
        } else {
            $resp['_status'] = 'OK';
        }
        if (isset($resp['_error'])) {
            // Considering a problem occured is an _error field is present ...
            $error = $resp['_error'];
            $error['message'] = "Url: ".$endpoint.". Message: ".$error['message'];
            if ($this->logger_debug) {
                error_log("backend error: ".$error['code'].", ".$error['message']);
            }
            throw new Exception($error['message'], $error['code']);
        }
        return $resp;
    }

    /**
     *
     * Get all items in the specified endpoint of alignak backend
     *
     * If an error occurs, a BackendException is raised.
     *
     * If the max_results parameter is not specified in parameters, it is set to
     * BACKEND_PAGINATION_LIMIT (backend maximum value) to limit requests number.
     *
     * This method builds a response that always contains: _items and _status::
     *
     *      {
     *          u'_items': [
     *              ...
     *          ],
     *          u'_status': u'OK'
     *      }
     *
     * @param string $endpoint endpoint (API URL) relative from root endpoint
     * @param array $params list of parameters for the backend API
     *
     * @return list of properties when query item | list of items when get many items
     */
    function get_all($endpoint, $params=array()) {
        if (is_null($this->token)) {
            if ($this->logger_debug) {
                error_log("Authentication is required for getting an object.");
            }
            throw new Exception("Access denied, please login before trying to get", 1001);
        }
        if ($this->logger_debug) {
            error_log("get_all, endpoint: ".$endpoint.", paramaters: ".print_r($params, TRUE));
        }

        // Set max results at maximum value supported by the backend to limit requests number
        if (empty($params)) {
            $params = array('max_results' => $this->BACKEND_PAGINATION_LIMIT);
        } else if (!isset($params['max_results'])) {
            $params['max_results'] = $this->BACKEND_PAGINATION_LIMIT;
        }

        // Get first page
        $last_page = FALSE;
        $items = array();
        if ($this->processes == 1) {
            while (!$last_page) {
                # Get elements ...
                $resp = $this->get($endpoint, $params);
                # Response contains:
                # _items:
                # ...
                # _links:
                #  self, parent, prev, last, next
                # _meta:
                # - max_results, total, page

                if (isset($resp['_links']['next'])) {
                    # Go to next page ...
                    $params['page'] = $resp['_meta']['page'] + 1;
                    $params['max_results'] = $resp['_meta']['max_results'];
                } else {
                    $last_page = TRUE;
                }
                $items = array_merge($items, $resp['_items']);
            }
        } else {
            // Get first page
            $resp = $this->get($endpoint, $params);
            $number_pages = ceil($resp['_meta']['total'] / $resp['_meta']['max_results']);

            $requests = function ($total, $endpoint, $params_get_str) {
                for ($i = 1; $i <= $total; $i++) {
                    $headers = [
                        'Authorization' => 'Basic '. base64_encode($this->token.':')
                    ];
                    yield new Request('GET', $this->url_endpoint_root."/".$endpoint."?".$params_get_str."&page=".$i, $headers);
                }
            };
            $params_get = array();
            foreach ($params as $key => $value) {
                if ($key != 'page') {
                    $params_get[] = $key."=".$value;
                }
            }
            $pages_errors = array();
            $params_get_str = implode("&", $params_get);
            $pool = new Pool($this->client,
                    $requests($number_pages, $endpoint, $params_get_str),
                    ['concurrency' => $this->processes,
                    'fulfilled' => function($response, $index) use (&$items) {
                        // this is delivered each successful response
                        $body = $response->getBody();
                        $resp = json_decode($body->getContents(), true);
                        $items = array_merge($items, $resp['_items']);
                    },
                    'rejected' => function ($reason, $index) use (&$pages_errors) {
                        echo "Rejected...";
                        echo $reason->getMessage();
                        array_push($pages_errors, $index);
                    }]);
            $promise = $pool->promise();
            $promise->wait();
            if (count($pages_errors) > 0 AND count($pages_errors) < $number_pages) {
                // so have pages in error but not all in errors
                foreach ($pages_errors as $page) {
                    $params['page'] = $page;
                    $resp = $this->get($endpoint, $params);
                    $items = array_merge($items, $resp['_items']);
                }
            }
        }
        return array(
            '_items'  => $items,
            '_status' => 'OK'
        );
    }


    /**
     * Create a new item
     *
     * @param type $endpoint endpoint (API URL)
     * @param type $data properties of item to create
     * @param type $headers headers (example: Content-Type)
     */
    function post($endpoint, $data, $headers=array()) {
        if (is_null($this->token)) {
            if ($this->logger_debug) {
                error_log("Authentication is required for adding an object.");
            }
            throw new Exception("Access denied, please login before trying to post", 1001);
        }

        if (empty($headers)) {
            $headers = array('Content-Type' => 'application/json');
        }

        $response = $this->client->request('POST', $this->url_endpoint_root.'/'.$endpoint, [
            'json' => $data,
            'auth' => [$this->token, '']
        ]);
        $body = $response->getBody();
        $resp = json_decode($body->getContents(), true);
        if (isset($resp['_status'])) {
            // Considering an information is returned if a _status field is present ...
            if ($this->logger_debug) {
                error_log("backend status: ".$resp['_status']);
            }
        }
        if (isset($resp['_error'])) {
            // Considering a problem occured is an _error field is present ...
            $error = $resp['_error'];
            $error['message'] = "Url: ".$endpoint.". Message: ".$error['message'];
            if (isset($resp['_issues'])) {
                foreach ($resp['_issues'] as $issue) {
                    if ($this->logger_debug) {
                        error_log(" - issue: ".$issue.": ".$resp['_issues'][$issue]);
                    }
                }
            }
            throw new Exception($error['message'], $error['code']);
        }
        return $resp;
    }

    /**
     * Method to update an item
     *
     *  The headers must include an If-Match containing the object _etag.
     *      headers = {'If-Match': contact_etag}
     *
     *  The data dictionary contain the fields that must be modified.
     *
     *  If the patching fails because the _etag object do not match with the provided one, a
     *  BackendException is raised with code = 412.
     *
     *  If inception is True, this method makes e new get request on the endpoint to refresh the
     *  _etag and then a new patch is called.
     *
     *  If an HTTP 412 error occurs, a BackendException is raised. This exception is:
     *  - code: 412
     *  - message: response content
     *  - response: backend response
     *
     *  All other HTTP error raises a BackendException.
     *  If some _issues are provided by the backend, this exception is:
     *  - code: HTTP error code
     *  - message: response content
     *  - response: JSON encoded backend response (including '_issues' dictionary ...)
     *
     *  If no _issues are provided and an _error is signaled by the backend, this exception is:
     *  - code: backend error code
     *  - message: backend error message
     *  - response: JSON encoded backend response
     *
     * @param type $endpoint endpoint (API URL)
     * @param type $data properties of item to update
     * @param type $headers headers (example: Content-Type). 'If-Match' required
     * @param type $inception if true tries to get the last _etag
     */
    function patch($endpoint, $data, $headers=array(), $inception=false) {

        if (is_null($this->token)) {
            if ($this->logger_debug) {
                error_log("Authentication is required for patching an object.");
            }
            throw new Exception("Access denied, please login before trying to patch", 1001);
        }
        if (empty($headers)) {
            if ($this->logger_debug) {
                error_log("Header If-Match is required for patching an object.");
            }
            throw new Exception("Header If-Match required for patching an object", 1005);
        }

        try {
            $response = $this->client->request('POST', $this->url_endpoint_root.'/'.$endpoint, [
                'json'    => $data,
                'headers' => $headers,
                'auth'    => [$this->token, ''],
                'http_errors' => true
            ]);
        } catch (ClientException $e) {
            if ($e->getStatusCode() == 412) {
                if ($inception) {
                    $resp = $this->get($endpoint);
                    $headers['If-Match'] = $resp['_etag'];
                    return $this->patch( $endpoint, $data, $headers, False);
                } else {
                    throw new Exception($e->getResponse(), 412);
                }
            }
        }
        if ($this->logger_debug) {
            error_log("Patching failed, response is: ".$response->getStatusCode()." / ".$response->getBody()->getContents());
        }
        $body = $response->getBody();
        $resp = json_decode($body->getContents(), true);
        if (isset($resp['_status'])) {
            // Considering an information is returned if a _status field is present ...
            if ($this->logger_debug) {
                error_log("backend status: ".$resp['_status']);
            }
        }
        if (isset($resp['_issues'])) {
            foreach ($resp['_issues'] as $issue) {
                if ($this->logger_debug) {
                    error_log(" - issue: ".$issue.": ".$resp['_issues'][$issue]);
                }
            }
            throw new Exception($body->getContents(), $response->getStatusCode());
        }
        if (isset($resp['_error'])) {
            $error = $resp['_error'];
            if ($this->logger_debug) {
                error_log("backend error: ".$error['code'].", ".$error['message']);
            }
            throw new Exception($error['message'], $error['code']);
        }
        return $resp;
    }

    /**
     * Method to delete an item or all items
     *
     *  headers['If-Match'] must contain the _etag identifier of the element to delete
     *
     * @param type $endpoint endpoint (API URL)
     * @param type $headers headers (example: Content-Type)
     */
    function delete($endpoint, $headers=array()) {
        if (is_null($this->token)) {
            if ($this->logger_debug) {
                error_log("Authentication is required for deleting an object.");
            }
            throw new Exception("Access denied, please login before trying to delete", 1001);
        }
        $data = array('auth' => array($this->token, ''));

        $response = $this->client->request('DELETE', $this->url_endpoint_root.'/'.$endpoint, [
            'auth' => [$this->token, ''],
            'headers' => $headers
        ]);
        return array();
    }
}
