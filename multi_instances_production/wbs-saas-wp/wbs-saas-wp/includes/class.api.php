<?php

namespace WBSSaaS;

use \Laminas\Config\Config;

/**
 * Class WBS SaaS Phoenix API
 * 
 * @link https://developer.wordpress.org/reference/functions/wp_remote_get/
 * 
 * @since 2.0.0
 * 
 */

class PhoenixAPI
{

    /**
     * URL of Pheonix API endpoint
     *
     * @var string|null
     */
    private ?string $endpoint;

    /**
     * The Authorization token of Phoenix API
     *
     * @var string|null
     */
    private ?string $token;

    /**
     * Object Logger to log the current class
     *
     * @var \WBSSaaS\Logger|null
     */
    private ?\WBSSaaS\Logger $log;

    /**
     * To store any message fomr the API
     *
     * @var string|null
     */
    private ?string $message = null;

    /**
     * Constructor
     *
     * @param   string  $location   Key of the location in ~/config/app
     * @param   object  $object     \WBSaaS\Logger object
     */
    public function __construct( string $location, object $log = null )
    {
        global $app;
        
        $this->endpoint = $app->location->$location->fqdn;
        $this->token    = $app->location->$location->token;

        if( $log ) {
            $this->log = $log;
        } else {
            $this->log = new \WBSSaaS\Logger( $this );
        }
    }

    /**
     * Check if the Phoenix API is alive or down
     *
     * @return boolean|object
     */
    public function alive(): bool|object
    {
        $arguments = array(
            'method' => 'GET',
            'httpversion' => '1.1',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            )
        );

        $response = wp_remote_get( $this->endpoint . '/v1/check-server', $arguments );

        return $this->processResponse( $response );
    }

     /**
      * Check Domain avaibility FQDN
      * OUTDATED since 2.2.x
      *
      * @param string $domain   The Fully Qualified Domain Name to check
      * @return boolean|object  False if error, or Phoenix API Response if everything is ok
      */
    public function checkDomain( string $domain ): bool|object
    {
        $url = $this->endpoint . '/v1/check-domains?' . http_build_query( array(
            'domain' => $domain,
        ), '&' );

        $arguments = array(
            'method' => 'GET',
            'httpversion' => '1.1',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'key' => $this->token,
            )
        );

        // $this->log->debug( [__METHOD__, __LINE__], $url );
        // $this->log->debug( [__METHOD__, __LINE__], $arguments );

        $response = wp_remote_get( $url, $arguments );

        return $this->processResponse( $response );
    }

    /**
     * Check if the tenant was migrated (Laravel migration)
     *
     * @param string $uuid      UUID of the tenant
     * @return boolean|object   False if error, or Phoenix API Response if everything is ok
     */
    public function checkMigration( string $uuid ): bool|object
    {
        $url = $this->endpoint . '/v1/clients/new/check?' . http_build_query( array(
            'u' => $uuid,
        ), '&' );

        $arguments = array(
            'method' => 'GET',
            'httpversion' => '1.1',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'key' => $this->token,
            )
        );

        $response = wp_remote_get( $url, $arguments );

        return $this->processResponse( $response );
    }

    /**
     * Create new Tenant
     *
     * @param   array           $payload
     * @return  boolean|object  False if error, or Phoenix API Response if everything is ok
     */
    public function createTenant( array $payload ): bool|object
    {
        $payload = json_encode( $payload );
        $this->log->debug( [__METHOD__, __LINE__], 'JSON for new client (Payload) => ' );
        $this->log->debug( [__METHOD__, __LINE__], $payload );

        $arguments  = array(
            'method' => 'POST',
            'httpversion' => '1.1',
            'timeout'     => 30, // We overwrite the WordPress default timeout
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'key' => $this->token
            ),
            'body' => $payload
        );

        $response = wp_remote_get( $this->endpoint . '/v1/clients/new' , $arguments );

        return $this->processResponse( $response );

    }

    /**
     * Update Expiration Date
     *
     * @param   array           $tenant
     * @return  boolean|object  False if error, or Phoenix API Response if everything is ok
     */
    public function updateExpirationDate( array $tenant ): bool|object
    {
        $payload = json_encode( $tenant );
        $this->log->debug( [__METHOD__, __LINE__], 'JSON for update expiration date => ' );
        $this->log->debug( [__METHOD__, __LINE__], $payload );

        $arguments  = array(
            'method' => 'PUT',
            'httpversion' => '1.1',
            'timeout'     => 30, // We overwrite the WordPress default timeout
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'key' => $this->token
            ),
            'body' => $payload
        );

        $response = wp_remote_get( $this->endpoint . '/v1/clients/update/expiration-date' , $arguments );

        return $this->processResponse( $response );
    }

    /**
     * Update Subscription Package
     * 
     * @param   object          $tenant     Object from the DB
     * @param   array           $package    Array of all the settings
     * @return  boolean|object              False if error, or Phoenix API Response if everything is ok
     */
    public function updatePackage( object $tenant, array $package ): bool|object
    {
        $payload = array(
            'company_uuid' => $tenant->tenant_uuid,
            'package'      => $package,
            'created'      => $tenant->created,
            'modified'     => current_time( 'mysql' ),
            'expired'      => $tenant->subscription_expired,
        );
        $payload = json_encode( $payload );
        $this->log->debug( [__METHOD__, __LINE__], 'JSON for update package WC-Addon => ' );
        $this->log->debug( [__METHOD__, __LINE__], $payload );

        $arguments  = array(
            'method' => 'PUT',
            'httpversion' => '1.1',
            'timeout'     => 30, // We overwrite the WordPress default timeout
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'key' => $this->token
            ),
            'body' => $payload
        );

        $response = wp_remote_get( $this->endpoint . '/v1/clients/update/packages' , $arguments );

        return $this->processResponse( $response );
    }

    /**
     * Store message to the object 
     *
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Process the response from the API
     *
     * @param mixed $response
     * @return boolean|object   Return false if error. Or object response from Phoenix API
     */
    private function processResponse( mixed $response ): bool|object
    {
        // $this->log->debug( [__METHOD__, __LINE__], 'Raw reponse from Phoenix API => ' );
        // $this->log->debug( [__METHOD__, __LINE__], $response );
        
        if( is_wp_error( $response ) ) {
            $error_code = array_key_first( $response->errors );
            $this->message = 'WordPress Error while wp_remote_get(). See log.';
            $this->log->error( [__METHOD__, __LINE__], 'WordPress Error while wp_remote_get(). See Error HTTP Response => ' );
            $this->log->error( [__METHOD__, __LINE__], $response->errors[$error_code][0] );
            return false;
        }

        if( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            $this->message = 'Unknown Error while wp_remote_get(). See log.';
            $this->log->error( [__METHOD__, __LINE__], 'Unknow Error while wp_remote_get(). See Code HTTP Response => ' . wp_remote_retrieve_response_code( $response ) );
            $this->log->error( [__METHOD__, __LINE__], $response );
            return false;
        }

        // Should be ok at this stage. Now we process the Phoenix API response 
        $response = wp_remote_retrieve_body( $response );
        $response = json_decode( $response) ;

        if( $response && isset( $response->success ) ) {

            if ( $response->success ) {

                return $response;

            } else {

                $this->message = 'Server is down or not reachable. See log.';
                $this->log->error( [__METHOD__, __LINE__], 'Server is down or not reachable. See Error(s) => ' );
                $this->log->error( [__METHOD__, __LINE__], $response->errors );
                return false;
            }

        } else {

            $this->message = 'The response from Phoenix API is not as expected. See log.';
            $this->log->error( [__METHOD__, __LINE__], 'The response from Phoenix API is not as expected. See response => ' );
            $this->log->error( [__METHOD__, __LINE__], $response );
            return false;
        }

    }

    public function listLanguages(): bool|object
    {
        $arguments = array(
            'method' => 'GET',
            'httpversion' => '1.1',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'key' => $this->token,
            )
        );

        $response = wp_remote_get( $this->endpoint . '/v1/wordpress/languages' , $arguments );

        return $this->processResponse( $response );

    }

}

/**
 * Class WBS SaaS Cloudflare API
 * 
 * @link https://developer.wordpress.org/reference/functions/wp_remote_get/
 * @link https://developers.cloudflare.com/api/
 * @link https://developers.cloudflare.com/api/operations/dns-records-for-a-zone-list-dns-records
 * 
 * @since 2.2.0
 * 
 */

class CloudflareAPI
{
    /**
     * Object Logger to log the current class
     *
     * @var \WBSSaaS\Logger|null
     */
    private ?\WBSSaaS\Logger $log;

    /**
     * Cloudflare base URL API
     *
     * @var string
     */
    private string $base_url;

    /**
     * Cloudflare config from $app->cloudflare
     */
    private object $config;

    /**
     * Constructor
     *
     * @param   string  $location   Key of the location in ~/config/app
     * @param   object  $object     \WBSaaS\Logger object
     */
    public function __construct( object $log = null )
    {
        global $app;

        $this->config = $app;
        $this->base_url = 'https://api.cloudflare.com/client/v4';

        if( $log ) {
            $this->log = $log;
        } else {
            $this->log = new \WBSSaaS\Logger( $this );
        }
    }

    /**
     * Prepare Headers for Cloudflare API
     * 
     * @param   string $method      HTTP method used: GET | POST | DELETE | PACTH | PUT
     * @param   array $body         Body to send
     * @return  array $headers      Complete headers to use in wp_remote_get()
     */
    private function prepareHeaders( string $method, array $body = null ): array {

        $headers = array(
            'method' => strtoupper( $method ),
            'httpversion' => '1.1',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->config->cloudflare->token,
            ),
        );

        if( !empty( $body ) ) {
            $headers['body'] = json_encode( $body );
        }

        return $headers;
    }

    /**
     * Process the response from Cloudflare API
     *
     * @param mixed $response
     * @return boolean|object   Return false if error. Or object response from Cloudflare API
     */
    private function processResponse( mixed $response ): bool|object
    {
        // $this->log->debug( [__METHOD__, __LINE__], 'Raw reponse sent to Cloudflare API => ' );
        // $this->log->debug( [__METHOD__, __LINE__], $response );
        
        if( is_wp_error( $response ) ) {
            $error_code = array_key_first( $response->errors );
            $this->log->error( [__METHOD__, __LINE__], 'WordPress Error while wp_remote_get(). See Error HTTP Response => ' );
            $this->log->error( [__METHOD__, __LINE__], $response->errors[$error_code][0] );
            return false;
        }

        if( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            $this->log->error( [__METHOD__, __LINE__], 'Unknow Error while wp_remote_get(). See Code HTTP Response => ' . wp_remote_retrieve_response_code( $response ) );
            $this->log->error( [__METHOD__, __LINE__], $response );
            return false;
        }

        // Should be ok at this stage. Now we process Cloudflare API response 
        $response = wp_remote_retrieve_body( $response );
        $response = json_decode( $response) ;

        return $response;

    }

    /**
     * Verify Token
     * 
     * @link https://developers.cloudflare.com/api/operations/user-api-tokens-verify-token
     * 
     * @return bool|object  Return false if error or Object as response
     */
    public function verifyToken(): bool|object
    {
        $response = wp_remote_get( $this->base_url . '/user/tokens/verify', $this->prepareHeaders( 'GET' ) );

        return $this->processResponse( $response );
    }

    /**
     * List DNS Record of a specific domain
     * 
     * @link https://developers.cloudflare.com/api/operations/dns-records-for-a-zone-list-dns-records
     * 
     * @param   string $domain  Cloudflare domain name 
     * @return  boolean|object  False if error, or Cloudflare API Response
     */
    public function listDNSRecords( string $domain ): bool|object
    {
        $response = wp_remote_get( $this->base_url . '/zones/'. $this->config->cloudflare->{$domain} . '/dns_records', $this->prepareHeaders( 'GET' ) );

        return $this->processResponse( $response );
    }

    /**
     * Check Domain availability on Cloudfalre DNS
     * 
     * @see listDNSRecords()
     * 
     * @param string $subdomain     The sudomain
     * @param string $domain        The domain name
     * @return boolean              true if exist OR false not found
     */
    public function checkAvailability( string $subdomain, string $domain ): bool
    {
        return ( is_object( $this->getDNSRecord( $subdomain, $domain ) ) ) ? true : false; 
    }

    /**
     * Create DNS Record for a specific domain
     * 
     * @link https://developers.cloudflare.com/api/operations/dns-records-for-a-zone-create-dns-record
     * 
     * @param   string $location    The location of the chosen server: staging | swiss | singapore | indonesia
     * @param   string $subdomain   The subdomain
     * @param   string $domain      The domain used
     * @return  boolean             false if error OR object of the A records created
     */
    public function createDNSRecord( string $location, string $subdomain, string $domain, string $comment = null ): bool|object
    {
        $body = array(
            'content' => $this->config->location->{$location}->ip, // IP Address
            'name' => $subdomain,
            'proxied' => ($location == 'staging') ? false : true,
            'type' => 'A',
            'comment' => 'CA ' . date("Y-m-d H:i") . '. ' . substr( $comment, 0 , 78),
        );

        $response = wp_remote_get(
            $this->base_url . '/zones/'. $this->config->cloudflare->{$domain} . '/dns_records',
            $this->prepareHeaders( 'POST', $body )
        );

        return $this->processResponse( $response );
    }

    /**
     * Get DNS Record Detail sfor a specific domain
     * 
     * @link https://developers.cloudflare.com/api/operations/dns-records-for-a-zone-dns-record-details
     * 
     * @param   string $subdomain   Subdomain to get
     * @param   string $domain      The domain used
     * @return  boolean             false if not found OR object of the A records found
     * 
     */
    public function getDNSRecord( string $subdomain, string $domain ): bool|object
    {
        $records = $this->listDNSRecords( $domain );
        $fqdn    = $subdomain . '.' . $domain;

        // $this->log->debug( [__METHOD__, __LINE__], $records->result );

        foreach( $records->result as $record ) {
            if( $record->name == $fqdn ) {
                return $record;
            }
        }

        return false;
    }

    /**
     * Delete DNS Record of a specific domain
     * 
     * @link https://developers.cloudflare.com/api/operations/dns-records-for-a-zone-delete-dns-record
     * 
     * @param   string $subdomain   Subdomain to delete
     * @param   string $domain      The domain used
     * @return  boolean             false if error OR object of the A records delete (result->id)
     * 
     */
    public function deleteDNSRecord( string $subdomain, string $domain ): bool|object
    {
        $record = $this->getDNSRecord( $subdomain, $domain );

        if( !is_object( $record ) ) {
            return false;
        }
        $this->log->info( [__METHOD__, __LINE__], 'Cloudflare ID record to delete => ' );
        $this->log->info( [__METHOD__, __LINE__], $record );

        $response = wp_remote_get(
            $this->base_url . '/zones/'. $this->config->cloudflare->{$domain} . '/dns_records/' . $record->id,
            $this->prepareHeaders( 'DELETE' )
        );

        return $this->processResponse( $response );
    }
}

/**
 * Class WBS SaaS Infomaniak API
 * 
 * @link https://developer.infomaniak.com/getting-started
 * @link https://developer.infomaniak.com/docs/api/get/1/mail_hostings/%7Bmail_hosting_id%7D/mailboxes
 * 
 * @since 2.2.0
 * 
 */

class InfomaniakAPI
{
/**
     * Object Logger to log the current class
     *
     * @var \WBSSaaS\Logger|null
     */
    private ?\WBSSaaS\Logger $log;

    /**
     * Infomaniak base URL API
     *
     * @var string
     */
    private string $base_url;

    /**
     * Infomaniak config from $app->infomaniak
     */
    private object $config;

    /**
     * Constructor
     *
     * @param   string  $location   Key of the location in ~/config/app
     * @param   object  $object     \WBSaaS\Logger object
     */
    public function __construct( object $log = null )
    {
        global $app;

        $this->config = $app;
        $this->base_url = 'https://api.infomaniak.com';

        if( $log ) {
            $this->log = $log;
        } else {
            $this->log = new \WBSSaaS\Logger( $this );
        }
    }

    /**
     * Prepare Headers for Infomaniak API
     * 
     * @param   string $method      HTTP method used: GET | POST | DELETE | PACTH | PUT
     * @return  array $headers      Complete headers to use in wp_remote_get()
     */
    private function prepareHeaders( string $method ): array {

        $headers = array(
            'method' => strtoupper( $method ),
            'httpversion' => '1.1',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->config->infomaniak->token,
            ),
        );

        return $headers;
    }

    /**
     * Process the response from Infomaniak API
     *
     * @param mixed $response
     * @return boolean|object   Return false if error. Or object response from Cloudflare API
     */
    private function processResponse( mixed $response ): bool|object
    {
        // $this->log->debug( [__METHOD__, __LINE__], 'Raw reponse sent to Infomaniak API => ' );
        // $this->log->debug( [__METHOD__, __LINE__], $response );
        
        if( is_wp_error( $response ) ) {
            $error_code = array_key_first( $response->errors );
            $this->log->error( [__METHOD__, __LINE__], 'WordPress Error while wp_remote_get(). See Error HTTP Response => ' );
            $this->log->error( [__METHOD__, __LINE__], $response->errors[$error_code][0] );
            return false;
        }

        if( 200 !== wp_remote_retrieve_response_code( $response ) ) {
            $this->log->error( [__METHOD__, __LINE__], 'Unknow Error while wp_remote_get(). See Code HTTP Response => ' . wp_remote_retrieve_response_code( $response ) );
            $this->log->error( [__METHOD__, __LINE__], $response );
            return false;
        }

        // Should be ok at this stage. Now we process Infomaniak API response 
        $response = wp_remote_retrieve_body( $response );
        $response = json_decode( $response) ;

        return $response;
    }

    /**
     * List al the mailboxed for a gien domain name
     * 
     * @link https://developer.infomaniak.com/docs/api/get/1/mail_hostings/%7Bmail_hosting_id%7D/mailboxes
     * 
     * @return bool|object  Return false if error or Object as response
     */
    public function listMailboxes( string $domain ): bool|object
    {
        $response = wp_remote_get( $this->base_url . '/1/mail_hostings/' . $this->config->infomaniak->inbox->{$domain} . '/mailboxes', $this->prepareHeaders( 'GET' ) );

        return $this->processResponse( $response );
    }

    /**
     * Count how many mailboxes for a given domain name
     * 
     * @link https://developer.infomaniak.com/docs/api/get/1/mail_hostings/%7Bmail_hosting_id%7D/mailboxes
     * 
     * @return bool|integer  Return false if error or Integer
     */
    public function countMailboxes( string $domain ): bool|int
    {
        $response = $this->listMailboxes( $domain );

        return count( $response->data );
    }

    /**
     * Get mailboxes IDN
     * 
     * @link https://developer.infomaniak.com/docs/api/get/1/mail_hostings/%7Bmail_hosting_id%7D/mailboxes
     * 
     * @return bool|array  Return false if error or Array of Mailbox IDN
     */
    public function getMailboxIDN( string $domain ): bool|array{

        $mailboxes = $this->listMailboxes( $domain );

        if( count( $mailboxes->data ) > 0 ) {

            $mailbox_idn = array();

            foreach( $mailboxes->data as $mailbox ) {
                $mailbox_idn[] = $mailbox->mailbox_idn;
            }
            return $mailbox_idn;
        }

        return false;
    }
}