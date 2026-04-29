<?php

namespace App\Facades;

use App\Helpers\CommonHelper;
use BadMethodCallException;
use Illuminate\Support\Facades\Session;

/**
 * Legacy static entry point used across blades. Forwards to CommonHelper where possible.
 * (Previously incorrectly extended Illuminate\Support\Facades\Facade.)
 */
class CommonFacades
{
    public static function __callStatic(string $method, array $arguments)
    {
        if ($method === 'getSessionCompanyId') {
            return Session::get('company_id') ?? '';
        }

        if ($method === 'changeDateFormat') {
            $date = $arguments[0] ?? '';

            return CommonHelper::changeDateformat($date);
        }

        if ($method === 'changeTimeFormat') {
            $time = $arguments[0] ?? '';
            if ($time === '' || $time === null) {
                return '';
            }
            $ts = strtotime((string) $time);

            return $ts ? date('h:i A', $ts) : (string) $time;
        }

        if ($method === 'headerPrintSectionInPrintView') {
            // Legacy print header; no implementation in CommonHelper — safe empty output
            return '';
        }

        if ($method === 'companyDatabaseConnection' || $method === 'reconnectMasterDatabase') {
            return null;
        }

        if ($method === 'checkUserPermissionForMenu') {
            // Legacy views expect '0' / '1' strings for JS — default allow
            return '1';
        }

        if (method_exists(CommonHelper::class, $method)) {
            return CommonHelper::$method(...$arguments);
        }

        throw new BadMethodCallException(
            sprintf('CommonFacades / CommonHelper has no method "%s".', $method)
        );
    }
}
