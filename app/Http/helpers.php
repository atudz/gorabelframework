<?php


use App\Factories\LibraryFactory;

if (!function_exists('feature_enabled')) {
    /**
     * Check whether the user/role has this feature
     * @param unknown $value
     * @param number $userId
     * @return unknown
     */
    function feature_enabled($value,$userId=0)
    {
        return LibraryFactory::getInstance('Feature')->userHasFeature([$value]);
    }
}