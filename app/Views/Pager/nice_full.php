<?php
?>
<?php if (isset($pager)): ?>
<nav class="pager-wrap" aria-label="Pagination">
    <ul class="pager-list">
        <?php 
            $links = $pager->links();
            $current = 1;
            if (is_array($links)) {
                foreach ($links as $lnk) {
                    if (!empty($lnk['active'])) { $current = (int) ($lnk['title'] ?? 1); break; }
                }
            }
            $last = 1;
            if (!empty($links)) {
                $lastLink = end($links);
                $last = (int) ($lastLink['title'] ?? 1);
                reset($links);
            }
        ?>
        <li class="pager-item <?= $pager->hasPreviousPage() ? '' : 'disabled' ?>">
            <a class="pager-link" href="<?= esc($pager->hasPreviousPage() ? $pager->getPreviousPage() : '#') ?>" aria-label="Previous">&lt;</a>
        </li>
        <li class="pager-item <?= ($current === 1) ? 'active' : '' ?>">
            <a class="pager-link" href="<?= esc($pager->getFirst()) ?>" aria-current="<?= ($current === 1) ? 'page' : 'false' ?>">1</a>
        </li>
        <?php if ($last > 2): ?>
            <li class="pager-item disabled"><span class="pager-link" style="pointer-events:none;">…</span></li>
            <li class="pager-item <?= ($current === $last) ? 'active' : '' ?>">
                <a class="pager-link" href="<?= esc($pager->getLast()) ?>" aria-current="<?= ($current === $last) ? 'page' : 'false' ?>"><?php echo $last; ?></a>
            </li>
        <?php endif; ?>
        <li class="pager-item <?= $pager->hasNextPage() ? '' : 'disabled' ?>">
            <a class="pager-link" href="<?= esc($pager->hasNextPage() ? $pager->getNextPage() : '#') ?>" aria-label="Next">&gt;</a>
        </li>
    </ul>
</nav>
<?php endif; ?>


