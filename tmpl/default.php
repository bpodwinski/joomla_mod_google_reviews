<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

$placeId = $reviews['placeId'] ?? null;

if (isset($reviews['error'])): ?>
    <div class="alert alert-danger">
        <p><?php echo htmlspecialchars($reviews['error']); ?></p>
    </div>

<?php else: ?>
    <div class="google-reviews mt-4">
        <?php if (!empty($reviews['reviews'])): ?>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                <?php foreach ($reviews['reviews'] as $review): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <?php if (!empty($review['authorAttribution']['photoUri'])): ?>
                                    <div class="d-flex align-items-center mb-3">
                                        <img
                                            src="<?php echo htmlspecialchars($review['authorAttribution']['photoUri']); ?>"
                                            alt="<?php echo htmlspecialchars(Text::_('MOD_GOOGLE_REVIEWS_AVATAR_ALT') . ' ' . ($review['authorAttribution']['displayName'] ?? '')); ?>"
                                            class="rounded-circle me-3"
                                            style="width: 50px; height: 50px; object-fit: cover;" />
                                        <h5 class="card-title mb-0">
                                            <?php echo htmlspecialchars($review['authorAttribution']['displayName'] ?? Text::_('MOD_GOOGLE_REVIEWS_ANONYMOUS')); ?>
                                        </h5>
                                    </div>
                                <?php endif; ?>

                                <p class="card-text">
                                    <?php
                                    echo htmlspecialchars(
                                        $review['text']['text'] ??
                                            $review['originalText']['text'] ??
                                            Text::_('MOD_GOOGLE_REVIEWS_NO_TEXT_AVAILABLE')
                                    );
                                    ?>
                                </p>

                                <p class="text-warning fw-bold mb-1">
                                    <?php echo Text::_('MOD_GOOGLE_REVIEWS_RATING'); ?>: <?php echo htmlspecialchars($review['rating'] ?? '0'); ?> / 5
                                </p>
                            </div>

                            <?php if (!empty($review['googleMapsUri'])): ?>
                                <div class="card-footer bg-white border-top-0 text-end">
                                    <a href="<?php echo htmlspecialchars($review['googleMapsUri']); ?>" target="_blank" rel="noopener" class="btn btn-link">
                                        <?php echo Text::_('MOD_GOOGLE_REVIEWS_VIEW_ON_GOOGLE_MAPS'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if ($placeId): ?>
                <div class="text-center mt-4">
                    <a href="https://www.google.com/maps/place/?q=place_id:<?php echo htmlspecialchars($placeId); ?>"
                        target="_blank" rel="noopener" class="btn btn-primary">
                        <?php echo Text::_('MOD_GOOGLE_REVIEWS_VIEW_ALL_REVIEWS'); ?>
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-muted"><?php echo Text::_('MOD_GOOGLE_REVIEWS_NO_REVIEWS'); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>