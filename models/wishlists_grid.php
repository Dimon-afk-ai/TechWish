<?php include('header.php'); ?>

<div class="wishlists-container">
    <h1>Публічні вішлісти</h1>
    
    <div class="wishlists-grid">
        <?php foreach ($wishlists['wishlists'] as $wishlist): ?>
            <div class="wishlist-card">
                <div class="wishlist-owner">
                    <img src="<?= htmlspecialchars($wishlist['avatar'] ?? 'default_avatar.jpg') ?>" 
                         alt="<?= htmlspecialchars($wishlist['username']) ?>" class="owner-avatar">
                    <span><?= htmlspecialchars($wishlist['username']) ?></span>
                </div>
                
                <h2>
                    <a href="wishlist.php?id=<?= $wishlist['id'] ?>">
                        <?= htmlspecialchars($wishlist['title']) ?>
                    </a>
                </h2>
                
                <?php if ($wishlist['description']): ?>
                    <p class="wishlist-description">
                        <?= htmlspecialchars(substr($wishlist['description'], 0, 100)) ?>
                        <?= strlen($wishlist['description']) > 100 ? '...' : '' ?>
                    </p>
                <?php endif; ?>
                
                <a href="wishlist.php?id=<?= $wishlist['id'] ?>" class="btn btn-primary">
                    Переглянути вішліст
                </a>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if ($wishlists['total'] > $wishlists['per_page']): ?>
        <div class="pagination">
            <?php
            $totalPages = ceil($wishlists['total'] / $wishlists['per_page']);
            $currentPage = $wishlists['page'];
            
            if ($currentPage > 1): ?>
                <a href="wishlists.php?page=1">&laquo;</a>
                <a href="wishlists.php?page=<?= $currentPage - 1 ?>">&lsaquo;</a>
            <?php endif;
            
            for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="wishlists.php?page=<?= $i ?>" <?= $i == $currentPage ? 'class="active"' : '' ?>>
                    <?= $i ?>
                </a>
            <?php endfor;
            
            if ($currentPage < $totalPages): ?>
                <a href="wishlists.php?page=<?= $currentPage + 1 ?>">&rsaquo;</a>
                <a href="wishlists.php?page=<?= $totalPages ?>">&raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include('footer.php'); ?>