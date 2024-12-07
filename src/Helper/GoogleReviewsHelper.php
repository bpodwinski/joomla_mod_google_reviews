<?php

namespace Bp\Module\GoogleReviews\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Cache\Controller\OutputController;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Log\Log;
use Joomla\Registry\Registry;

class GoogleReviewsHelper
{
    private const MOD_NAME = 'mod_google_reviews';

    private static $supportedLanguages = [
        // List of supported languages
        'af',
        'sq',
        'am',
        'ar',
        'hy',
        'az',
        'eu',
        'be',
        'bn',
        'bs',
        'bg',
        'my',
        'ca',
        'zh',
        'zh-CN',
        'zh-HK',
        'zh-TW',
        'hr',
        'cs',
        'da',
        'nl',
        'en',
        'eo',
        'et',
        'fi',
        'fr',
        'fy',
        'gl',
        'ka',
        'de',
        'el',
        'gu',
        'iw',
        'hi',
        'hu',
        'is',
        'id',
        'it',
        'ja',
        'jv',
        'kn',
        'kk',
        'km',
        'ko',
        'ky',
        'lo',
        'lv',
        'lt',
        'mk',
        'ms',
        'ml',
        'mr',
        'mn',
        'ne',
        'no',
        'or',
        'fa',
        'pl',
        'pt',
        'pa',
        'ro',
        'ru',
        'sr',
        'si',
        'sk',
        'sl',
        'es',
        'sw',
        'sv',
        'ta',
        'te',
        'th',
        'tr',
        'uk',
        'ur',
        'uz',
        'vi',
        'zu'
    ];

    /**
     * Fetches Google Reviews for a given place ID.
     *
     * @param Registry $params Configuration parameters.
     *
     * @return array The Google Reviews or an error message.
     */
    public static function getGoogleReviews($params)
    {
        // Determine language
        $language = Factory::getApplication()->getLanguage()->getTag();
        $languageCode = substr($language, 0, 2);

        // Fallback to English if language is unsupported
        if (!in_array($languageCode, self::$supportedLanguages)) {
            $languageCode = 'en';
        }

        $placeId = $params->get('place_id');
        $apiKey = $params->get('api_key');

        if (empty($apiKey) || empty($placeId)) {
            return [
                'error' => 'API key or Place ID is missing. Please configure the module properly.'
            ];
        }

        // Initialize cache
        $cacheKey = 'google_reviews' . $placeId . $languageCode;
        /** @var OutputController $cache */
        $cache = Factory::getContainer()->get(CacheControllerFactoryInterface::class)->createCacheController('output', [
            'defaultgroup' => self::MOD_NAME,
            'lifetime' => (int) $params->get('cache_lifetime', 60) * 60,
        ]);

        // Check cache
        if ($cache->contains($cacheKey)) {
            return $cache->get($cacheKey);
        } else {

            // Build API URL
            $url = sprintf(
                'https://places.googleapis.com/v1/places/%s?fields=displayName,reviews,formattedAddress,userRatingCount,rating&languageCode=%s&key=%s',
                urlencode($placeId),
                urlencode($languageCode),
                urlencode($apiKey)
            );

            try {
                $http = HttpFactory::getHttp(new Registry);
                $response = $http->get($url);

                if ($response->code !== 200) {
                    throw new \Exception('Error connecting to the Google API. HTTP Code: ' . $response->code);
                }

                $data = json_decode($response->body, true);
                /*                 echo '<pre>';
                var_dump($data);
                echo '</pre>';
                die;
 */
                // Check if reviews exist
                if (!empty($data['reviews'])) {
                    $result = [
                        'placeId' => $placeId,
                        'reviews' => array_slice($data['reviews'], 0, 5)
                    ];

                    // Store in cache
                    $cache->store($result, $cacheKey);
                    Log::add('Data stored in cache for ID: ' . $cacheKey, Log::INFO, self::MOD_NAME);

                    return $result;
                }

                return [
                    'error' => 'No reviews found for this Place ID.',
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
}
