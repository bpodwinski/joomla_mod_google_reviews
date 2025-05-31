<?php

namespace Bp\Module\GoogleReviews\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Log\Log;
use Joomla\Registry\Registry;

class GoogleReviewsHelper
{
    private const MOD_NAME = 'mod_google_reviews';

    private static $supportedLanguages = [
        // List of supported languages
        'af', 'sq', 'am', 'ar', 'hy', 'az', 'eu', 'be', 'bn', 'bs', 'bg', 'my', 'ca',
        'zh', 'zh-CN', 'zh-HK', 'zh-TW', 'hr', 'cs', 'da', 'nl', 'en', 'eo', 'et', 'fi',
        'fr', 'fy', 'gl', 'ka', 'de', 'el', 'gu', 'iw', 'hi', 'hu', 'is', 'id', 'it',
        'ja', 'jv', 'kn', 'kk', 'km', 'ko', 'ky', 'lo', 'lv', 'lt', 'mk', 'ms', 'ml',
        'mr', 'mn', 'ne', 'no', 'or', 'fa', 'pl', 'pt', 'pa', 'ro', 'ru', 'sr', 'si',
        'sk', 'sl', 'es', 'sw', 'sv', 'ta', 'te', 'th', 'tr', 'uk', 'ur', 'uz', 'vi', 'zu'
    ];

    /**
     * Retrieves Google reviews for a given Place ID.
     *
     * @param  Registry $params Module configuration parameters.
     * @return array  Google reviews data or an error message.
     */
    public static function getGoogleReviews($params)
    {
        // Determine application language
        $language = Factory::getApplication()->getLanguage()->getTag();
        $languageCode = substr($language, 0, 2);
        if (!in_array($languageCode, self::$supportedLanguages)) {
            $languageCode = 'en';
        }

        $placeId = $params->get('place_id');
        $apiKey  = $params->get('api_key');

        if (empty($apiKey) || empty($placeId)) {
            return [
                'error' => 'API key or Place ID is missing. Please configure the module correctly.'
            ];
        }

        // Cache duration in minutes (configurable via the module settings)
        $cacheDuration = (int) $params->get('cache_duration', 1440);

        // Define the cache directory
        $cacheDir = JPATH_CACHE . '/mod_google_reviews';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }

        // Unique cache file based on Place ID and language
        $cacheFile = $cacheDir . '/' . md5('google_reviews' . $placeId . $languageCode) . '.json';

        // Check if cache exists and is still valid
        if (file_exists($cacheFile)) {
            $jsonData = file_get_contents($cacheFile);
            $decoded  = json_decode($jsonData, true);

            if ($decoded && isset($decoded['timestamp']) && isset($decoded['data'])) {

                // If cache is still valid, return cached data
                if (time() < $decoded['timestamp'] + ($cacheDuration * 60)) {
                    Log::add('Data retrieved from cache (JSON file)', Log::INFO, self::MOD_NAME);

                    return $decoded['data'];
                }
            }
        }

        // Build the Google Places API URL
        $url = sprintf(
            'https://places.googleapis.com/v1/places/%s?fields=displayName,reviews,formattedAddress,userRatingCount,rating&languageCode=%s&key=%s',
            urlencode($placeId),
            urlencode($languageCode),
            urlencode($apiKey)
        );

        try {
            $http     = HttpFactory::getHttp(new Registry);
            $response = $http->get($url);

            if ($response->code !== 200) {
                throw new \Exception('Error connecting to the Google API. HTTP Code: ' . $response->code);
            }

            $data = json_decode($response->body, true);

            // If reviews are present, prepare the result array
            if (!empty($data['reviews'])) {
                $result = [
                    'placeId' => $placeId,
                    'reviews' => $data['reviews']
                ];

                // Prepare the cache data with the current timestamp
                $toSave = [
                    'timestamp' => time(),
                    'data'      => $result
                ];

                // Save to JSON cache file
                file_put_contents($cacheFile, json_encode($toSave));
                Log::add('Data stored in JSON cache file: ' . $cacheFile, Log::INFO, self::MOD_NAME);
                Log::add('Google API call performed, cache updated', Log::INFO, self::MOD_NAME);


                return $result;
            }

            return [
                'error'   => 'No reviews found for this Place ID.',
                'placeId' => $placeId
            ];

        } catch (\Exception $e) {
            Log::add($e->getMessage(), Log::ERROR, self::MOD_NAME);

            return [
                'error' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }
}
