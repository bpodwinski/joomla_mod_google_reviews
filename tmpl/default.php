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

$displayMode = $params->get('displayMode', 'grid');
$itemsPerSlide = (int) $params->get('itemsPerSlide', 3);
$placeId = $reviews['placeId'] ?? null;

if (isset($reviews['error'])): ?>
    <div class="alert alert-danger">
        <p><?php echo htmlspecialchars($reviews['error']); ?></p>
    </div>

<?php else: ?>

    <?php if ($displayMode === 'grid'): ?>
        <!-- Grid View -->
        <div id="googleReviewsGrid" class="google-reviews grid mt-4 d-flex justify-content-center align-items-center">
            <?php if (!empty($reviews['reviews'])): ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 justify-content-center">
                    <?php foreach ($reviews['reviews'] as $review): ?>
                        <div class="col">
                            <div class="card h-100">
                                <div class="card-body">
                                    <!-- Avatar and Details -->
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

                                    <!-- Review Text -->
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
                                            font-size: 30px; 
                                            position: absolute; 
                                            top: 1rem; 
                                            right: 1rem; 
                                            opacity: 0.1;
                                            color: #444444;
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

    <?php elseif ($displayMode === 'carousel'): ?>
        <!-- Carousel View -->
        <div id="googleReviewsCarousel" class="google-reviews carousel slide mt-4 h-100" data-bs-ride="carousel" data-bs-touch="true">
            <div class="carousel-inner">
                <?php
                $groupedReviews = array_chunk($reviews['reviews'], $itemsPerSlide);
                foreach ($groupedReviews as $index => $reviewGroup): ?>
                    <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                        <div class="row gx-3 gy-4 d-flex align-items-stretch">
                            <?php foreach ($reviewGroup as $review): ?>
                                <div class="col-md-<?php echo 12 / $itemsPerSlide; ?> d-flex align-items-stretch">
                                    <div class="card">
                                        <div class=" card-body d-flex flex-column">
                                            <!-- Avatar and Details -->
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

                                            <!-- Review Text -->
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
                                        </div>

                                        <i class="fab fa-google"
                                            style="
                                            font-size: 26px; 
                                            position: absolute; 
                                            top: 1rem; 
                                            right: 1rem; 
                                            opacity: 0.1;
                                            color: #444444;
                                            pointer-events: none;
                                        ">
                                        </i>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if ($placeId): ?>
            <div class="text-center mt-4">
                <a href="https://www.google.com/maps/place/?q=place_id:<?php echo htmlspecialchars($placeId); ?>"
                    target="_blank" rel="noopener" class="btn btn-primary">
                    <?php echo Text::_('MOD_GOOGLE_REVIEWS_VIEW_ALL_REVIEWS'); ?>
                </a>
            </div>
        <?php endif; ?>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const carousel = document.querySelector('#googleReviewsCarousel');
                const bsCarousel = new bootstrap.Carousel(carousel, {
                    interval: 5000, // Temps entre les slides
                    touch: true, // Active le glissement tactile
                });

                // Fonction pour ajuster toutes les cartes à la hauteur maximale globale
                function setUniformCardHeight() {
                    const allCards = carousel.querySelectorAll('.card');
                    let maxHeight = 0;

                    // Déterminer la hauteur maximale parmi toutes les cartes
                    allCards.forEach(card => {
                        const cardHeight = card.offsetHeight;
                        if (cardHeight > maxHeight) {
                            maxHeight = cardHeight;
                        }
                    });

                    // Appliquer cette hauteur maximale à toutes les cartes
                    allCards.forEach(card => {
                        card.style.height = maxHeight + 'px';
                    });
                }

                // Appelle la fonction après le chargement initial
                setUniformCardHeight();

                // Réajuster les hauteurs lors du redimensionnement de la fenêtre
                window.addEventListener('resize', setUniformCardHeight);

                let startX;

                // Gestion des événements de glissement
                carousel.addEventListener('touchstart', function(e) {
                    startX = e.touches[0].clientX;
                });

                carousel.addEventListener('touchmove', function(e) {
                    if (!startX) return;

                    const deltaX = e.touches[0].clientX - startX;
                    if (Math.abs(deltaX) > 50) {
                        if (deltaX > 0) {
                            bsCarousel.prev(); // Slide précédent
                        } else {
                            bsCarousel.next(); // Slide suivant
                        }
                        startX = null; // Réinitialise après un glissement
                    }
                });

                // Ajout du swiping avec la souris (optionnel)
                carousel.addEventListener('mousedown', function(e) {
                    startX = e.clientX;
                });

                carousel.addEventListener('mouseup', function(e) {
                    if (!startX) return;

                    const deltaX = e.clientX - startX;
                    if (Math.abs(deltaX) > 50) {
                        if (deltaX > 0) {
                            bsCarousel.prev();
                        } else {
                            bsCarousel.next();
                        }
                        startX = null;
                    }
                });
            });
        </script>

    <?php endif; ?>

<?php endif; ?>