<?php

namespace WBSSaaS;

use DateTime;
use Laminas\Config\Config;

/**
 * Class WBSSaaS\Logger
 * 
 * @since 2.0.0
 * 
 */

class Logger
{
    /**
     * WordPress User ID. Used in the constructor because sometimes it's out of the scope of the WP Hooks:
     * 
     * @var integer|null
     */
    private ?int $userId;

    /**
     * WordPress User Identifier (user_login)
     *
     * @var string|null
     */
    private ?string $userLogin;

    /**
     * Store the Wordpress Session ID
     *
     * @var string|null
     */
    private ?string $sessionId;

    /**
     * Store the User IP Address
     * 
     * @var string|null
     */
    private ?string $userIP;

    /**
     * Get the integer object handle
     *
     * @var integer|null
     */
    private ?int $objectId;

    /**
     * Get the hash ID. The unique identifier
     *
     * @var string|null
     */
    private ?string $objectHash;

    /**
     * Get Class Name for the header
     *
     * @var string|null
     */
    private ?string $className;

    /**
     * Get HTTP referer
     *
     * @var string|null
     */
    private ?string $referer;

    /**
     * Get User Agent (Browser)
     * 
     * @var string|false
     */
    private ?string $agent;

    /**
     * Get Hostname from IP
     * 
     * @var string|false
     */
    private ?string $hostname;

    /**
     * Get URI
     * 
     * @var string
     */
    private ?string $uri;

    /**
     * Get the App config
     *
     * @var object
     */
    private object $config;

    /**
     * Contructor
     *
     * @param   object|null $object
     * @param   int         $user_wp_id
     */
    public function __construct( object $object = null, int $user_wp_id = null )
    {
        $this->userId     = $user_wp_id ?? get_current_user_id() ?? 0;
        $this->userLogin  = $this->getWPUserLogin();
        $this->userIP     = $this->getUserIP();
        $this->sessionId  = $this->getWPSession();
        $this->objectId   = $this->getObjectId( $object );
        $this->objectHash = $this->getObjectHash( $object );
        $this->className  = $this->getClassName( $object );
        $this->referer    = $this->getReferer();
        $this->agent      = $this->getUserAgent();
        $this->hostname   = $this->getHostname();
        $this->uri        = $this->getURI();

        // Get the App config
        $this->config = new \Laminas\Config\Config( include WBSSAAS_PLUGIN_DIR . 'config/app.php' );

        // Header
        $this->push( $this->addHeader() );

        // Initialisation
        $this->addEntry( [__METHOD__, __LINE__], 'Initialization of Object #' . $this->objectId, 'info' );
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        // Destruction
        $this->addEntry( [__METHOD__, __LINE__], 'Deallocation of Object #' . $this->objectId, 'info' );
    }

    /**
     * Add a log entry with a diagnostic message for the developer.
     */
    public function debug( array $context, mixed $message ): void
    {
        $this->addEntry( $context, $message, 'debug');
    }

    /**
     * Add a log entry with an informational message for the user.
     */
    public function info( array $context, mixed $message ): void
    {
        $this->addEntry( $context, $message, 'info');
    }

    /**
     * Add a log entry with a warning message.
     */
    public function warning( array $context, mixed $message ): void
    {
        $this->addEntry( $context, $message, 'warning');
    }

    /**
     * Add a log entry with an error - usually followed by script termination.
     */
    public function error( array $context, mixed $message ): void
    {
        $this->addEntry( $context, $message, 'error');
    }

    /**
     * Get  the WordPress User identifier (user_login)
     *
     * @return string
     */
    private function getWPUserLogin(): string
    {
        if ( $this->userId > 0 ) {
            $wp_user = get_user_by( 'id', $this->userId );
            return $wp_user->user_login;
        }
        else {
            return 'Guest';
        }
    }

    /**
     * Get the current session token from WordPress
     * 
     * @link https://developer.wordpress.org/reference/functions/wp_get_session_token/
     *
     * @return string
     */
    private function getWPSession(): string
    {
        return !empty( wp_get_session_token() ) ? wp_get_session_token() : 'No Session Token';
    }

    /**
     * Get the user IP Address
     * 
     * @return string
     */
    private function getUserIP(): string
    {
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * Get the object ID for Header
     *
     * @param object|null $object
     * @return integer
     */
    private function getObjectId( ?object $object ): int
    {
        return is_null( $object ) ? spl_object_id( $this ) : spl_object_id( $object );
    }

    /**
     * Get the object Hash for Header (unique ID)
     *
     * @param object|null $object
     * @return string
     */
    private function getObjectHash( ?object $object ): string
    {
        return is_null( $object ) ? spl_object_hash( $this ) : spl_object_hash( $object );
    }

    /**
     * Get the class name for the header
     *
     * @param object|null $object
     * @return string
     */
    private function getClassName( ?object $object): string
    {
        return is_null( $object ) ? get_class( $this ) : get_class( $object );
    }

    /**
     * Get the now timestamp
     *
     * @return string
     */
    private function timestamp(): string
    {
        $now = new \DateTime('now', new \DateTimeZone( 'Asia/Jakarta' ) );
        return $now->format('d-m-Y H:i:s.u');
    }

    private function getReferer(): string
    {
        return isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : 'No referer';
    }

    private function getUserAgent(): string
    {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    private function getHostname(): string
    {
        return gethostbyaddr( $this->userIP );
    }

    private function getURI(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * Generate a new header from a new set of log entries with relevant information
     *
     * @return string
     */
    private function addHeader(): string
    {
        $header  = '----------------------------------------------------------------------------------------------------------------------------------------------' . PHP_EOL;
        $header .= 'Object ID    : ' . $this->objectId  . ' / ' . $this->objectHash . PHP_EOL;
        $header .= 'Class        : ' . $this->className . PHP_EOL;
        $header .= 'User         : ' . $this->userLogin . ' / ID:' . $this->userId . PHP_EOL;
        $header .= 'Session      : ' . $this->sessionId . PHP_EOL;
        $header .= 'Referer      : ' . $this->referer . PHP_EOL;
        $header .= 'URI          : ' . $this->uri . PHP_EOL;
        $header .= 'User Agent   : ' . $this->agent . PHP_EOL;
        $header .= 'IP / Hostname: ' . $this->userIP . ' / ' . $this->hostname . PHP_EOL;

        return $header;
    }

    private function addEntry( array $context, mixed $message, string $level )
    {
        // Make sure the message is stringifield
        if( is_array( $message ) || is_object( $message ) ) {
            $message = print_r( $message, true );
        }

        // format the context
        $namespace = str_word_count( $context[0], 1 );
        $namespace = implode( '.', $namespace );
        $namespace = $namespace . ':' . $context[1];
        
        // format the log entry
        $entry = $this->timestamp() . ' [' . strtoupper( $level ) . '] ' . $namespace . ' : ' . $message . PHP_EOL; 
        $this->push( $entry );
    }

    /**
     * Just push into the log file a content
     * 
     * @param string $content
     * @return void
     */
    private function push( string $content ): void
    {
        $file = $this->config->log->path . 'UserID-' . $this->userId . '.log';
        file_put_contents( $file, $content, FILE_APPEND );
    }
}
