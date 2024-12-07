<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

function generateStars($rating)
{
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
    $emptyStars = 5 - $fullStars - $halfStar;

    $starsHtml = str_repeat('<i class="fas fa-star text-warning"></i>', $fullStars);
    $starsHtml .= str_repeat('<i class="fas fa-star-half-alt text-warning"></i>', $halfStar);
    $starsHtml .= str_repeat('<i class="far fa-star text-muted"></i>', $emptyStars);

    return $starsHtml;
}

$placeId = $reviews['placeId'] ?? null;

if (isset($reviews['error'])): ?>
    <div class="alert alert-danger">
        <p><?php echo htmlspecialchars($reviews['error']); ?></p>
    </div>

<?php else: ?>
    <div class="google-reviews mt-4 d-flex justify-content-center align-items-center">
        <?php if (!empty($reviews['reviews'])): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
                <?php foreach ($reviews['reviews'] as $review): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">

                                    <div>
                                        <a href="<?php echo htmlspecialchars($review['googleMapsUri']); ?>" target="_blank" rel="noopener">
                                            <img
                                                src="<?php echo htmlspecialchars($review['authorAttribution']['photoUri']); ?>"
                                                alt="<?php echo htmlspecialchars(Text::_('MOD_GOOGLE_REVIEWS_AVATAR_ALT') . ' ' . ($review['authorAttribution']['displayName'] ?? '')); ?>"
                                                class="rounded-circle"
                                                style="width: 50px; height: 50px; object-fit: cover;" />
                                        </a>
                                    </div>

                                    <div class="ms-3">
                                        <h5 class="card-title mb-1">
                                            <a href="<?php echo htmlspecialchars($review['googleMapsUri']); ?>" target="_blank" rel="noopener">
                                                <?php echo htmlspecialchars($review['authorAttribution']['displayName'] ?? Text::_('MOD_GOOGLE_REVIEWS_ANONYMOUS')); ?>
                                            </a>
                                        </h5>
                                        <div class="text-warning fw-bold">
                                            <?php echo generateStars($review['rating'] ?? 0); ?>
                                        </div>

                                        <?php if (!empty($review['relativePublishTimeDescription'])): ?>
                                            <p class="text-muted small mb-0">
                                                <?php echo Text::_('MOD_GOOGLE_REVIEWS_TIME_POSTED'); ?>:
                                                <?php echo htmlspecialchars($review['relativePublishTimeDescription']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <p class="card-text">
                                    <?php
                                    $maxLength = (int) $params->get('maxLength', 250);
                                    $fullText = $review['text']['text'] ??
                                        $review['originalText']['text'] ??
                                        Text::_('MOD_GOOGLE_REVIEWS_NO_TEXT_AVAILABLE');
                                    if (strlen($fullText) > $maxLength) {
                                        $truncatedText = substr($fullText, 0, $maxLength) . '...';
                                        echo htmlspecialchars($truncatedText);
                                    } else {
                                        echo htmlspecialchars($fullText);
                                    }
                                    ?>
                                </p>
                                <i class="fab fa-google"
                                    style="
                                        font-size: 32px; 
                                        position: absolute; 
                                        bottom: 1rem; 
                                        right: 1rem; 
                                        opacity: 0.1;
                                        color: #333;
                                        pointer-events: none;
                                    ">
                                </i>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted"><?php echo Text::_('MOD_GOOGLE_REVIEWS_NO_REVIEWS'); ?></p>
        <?php endif; ?>
    </div>

    <?php if ($placeId): ?>
        <div class="text-center mt-4">
            <a href="https://www.google.com/maps/place/?q=place_id:<?php echo htmlspecialchars($placeId); ?>"
                target="_blank" rel="noopener" class="btn btn-primary">
                <?php echo Text::_('MOD_GOOGLE_REVIEWS_VIEW_ALL_REVIEWS'); ?>
            </a>
        </div>
    <?php endif; ?>

<?php endif; ?>