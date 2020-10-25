<?php

namespace App\Helper;

class SessionHelper
{
    private static $sessionHelper = null;
    private static $sessionStart = false;

    /**
     * @return SessionHelper
     */
    public static function sessionHelper()
    {
        if (self::$sessionHelper === null) {
            self::$sessionHelper = new SessionHelper();
        }

        return self::$sessionHelper;
    }

    /**
     * Session start
     */
    public static function sessionStart()
    {
        if (!self::$sessionStart) {
            session_start();
            self::$sessionStart = true;
        }
    }

    /**
     * @param string $key
     * @param $value
     * @param string|null $key2
     * @param string|null $key3
     */
    public function set($key, $value, $key2 = null, $key3 = null)
    {
        if ($key2 === null) {
            $_SESSION[$key] = $value;
        } elseif ($key3 === null) {
            $_SESSION[$key][$key2] = $value;
        } else {
            $_SESSION[$key][$key2][$key3] = $value;
        }
    }

    /**
     * @param string $key
     * @param string|null $key2
     * @param string|null $key3
     *
     * @return bool|mixed
     */
    public function get($key, $key2 = null, $key3 = null)
    {
        if ($key2 === null) {
            return (isset($_SESSION[$key])) ? $_SESSION[$key] : false;
        } elseif ($key3 === null) {
            return (isset($_SESSION[$key][$key2])) ? $_SESSION[$key][$key2] : false;
        }
        return (isset($_SESSION[$key][$key2][$key3])) ? $_SESSION[$key][$key2][$key3] : false;
    }

    /**
     * @param string $key
     * @param string|null $key2
     * @param string|null $key3
     *
     * @return bool
     */
    public function exist($key, $key2 = null, $key3 = null)
    {
        if ($key2 === null) {
            return isset($_SESSION[$key]) ? true : false;
        } elseif ($key3 === null) {
            return isset($_SESSION[$key][$key2]) ? true : false;
        }

        return isset($_SESSION[$key][$key2][$key3]) ? true : false;
    }

    /**
     * @param string $key
     * @param string|null $key2
     * @param string|null $key3
     */
    public function delete($key, $key2 = null, $key3 = null)
    {
        if ($key2 === null) {
            if (isset($_SESSION[$key])) {
                unset($_SESSION[$key]);
            }
        } elseif ($key3 === null) {
            if (isset($_SESSION[$key][$key2])) {
                unset($_SESSION[$key][$key2]);
            }
        } else {
            if (isset($_SESSION[$key][$key2][$key3])) {
                unset($_SESSION[$key][$key2][$key3]);
            }
        }
    }

    /**
     * Destroy session
     */
    public static function destroy()
    {
        if (isset($_SESSION)) {
            session_destroy();
        }
    }

    /**
     * Redirect or return bool
     * @return bool
     */
    public function adminPermission()
    {
        if (!$this->exist('user') || $this->exist('user', 'isAdmin') == false) {
            header('Location: ?target=home&action=showMainPage');
        }

        return true;
    }
}