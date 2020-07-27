<?php
$order = false;
$order_products = false;
$order_first_product = false;

if (isset($item['rel_id']) AND !isset($is_order)) {
    $item_id = $item['rel_id'];
} elseif (isset($item['id']) AND isset($is_order)) {
    $item_id = $item['id'];
}

$order = get_order_by_id($item_id);
$order_products = mw()->shop_manager->order_items($item_id);
if ($order_products) {
    $order_first_product = $order_products[0];
}

$created_by = false;
if (isset($item['created_by'])) {
    $created_by = get_user_by_id($item['created_by']);
    $created_by_username = $created_by['username'];
}
?>

<div class="card mb-2 not-collapsed-border collapsed <?php if (!isset($is_order)): ?>card-bubble<?php endif; ?> card-order-holder bg-silver" data-toggle="collapse" data-target="#notif-order-item-<?php print $item_id; ?>" aria-expanded="false" aria-controls="collapseExample">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col item-image">
                        <div class="img-circle-holder w-60">
                            <?php if ($order_first_product AND isset($order_first_product['item_image'])): ?>
                                <img src="<?php echo thumbnail($order_first_product['item_image'], 160, 160); ?>"/>
                            <?php else: ?>
                                <img src="<?php echo thumbnail(''); ?>"/>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="col item-id"><span class="text-primary">#<?php echo $order['id']; ?></span></div>

                    <div class="col item-title" style="min-width: 210px;">
                        <?php if ($order_first_product AND isset($order_first_product['title'])): ?>
                            <span class="text-primary text-break-line-2"><?php echo $order_first_product['title']; ?></span>
                        <?php endif; ?>

                        <?php if (isset($created_by_username)): ?>
                            <small class="text-muted">Ordered by: <?php echo $created_by_username; ?></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col-6 col-sm-4 col-md item-amount">
                        <?php if (isset($order['amount'])): ?><?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?><br/><?php endif; ?>
                        <?php if (isset($order['is_paid'])): ?>
                            <small class="text-muted"><?php echo $order['is_paid']; ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="col-6 col-sm-4 col-md item-date" data-toggle="tooltip" title="<?php print mw('format')->ago($item['created_at']); ?>">
                        <?php print date('M d, Y', strtotime($item['created_at'])); ?><br/>
                        <small class="text-muted"><?php print date('h:s', strtotime($item['created_at'])); ?>h</small>
                    </div>

                    <div class="col-12 col-sm-4 col-md item-status">
                        <?php if (isset($item['is_read']) && $item['is_read'] == '0'): ?>
                            <span class="text-success">New</span><br/>
                        <?php endif; ?>
                        <small class="text-muted">&nbsp;</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="collapse" id="notif-order-item-<?php print $item_id; ?>">
            <div class="row mt-3">
                <div class="col-12 text-center text-sm-left">
                    <a href="<?php print admin_url('view:shop/action:orders#vieworder=' . $order['id']); ?>" class="btn btn-primary btn-sm btn-rounded">View order</a>
                </div>
            </div>

            <hr class="thin"/>

            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <h6><strong>Customer Information</strong></h6>

                    <div>
                        <small class="text-muted">Client name:</small>
                        <p>
                            <?php if (isset($order['first_name']) OR isset($order['last_name'])): ?>
                                <?php if (isset($order['first_name'])): ?><?php echo $order['first_name'] . ' '; ?><?php endif; ?>
                                <?php if (isset($order['last_name'])): ?><?php echo $order['last_name']; ?><?php endif; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted">E-mail:</small>
                        <p>
                            <?php if (isset($order['email'])): ?>
                                <?php echo $order['email']; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted">Phone:</small>
                        <p>
                            <?php if (isset($order['phone'])): ?>
                                <?php echo $order['phone']; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong>Payment Information</strong></h6>

                    <div>
                        <small class="text-muted">Amount:</small>
                        <p>
                            <?php if (isset($order['amount'])): ?>
                                <?php echo currency_format($order['amount']) . ' ' . $order['payment_currency']; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted">Payment method:</small>
                        <p>
                            <?php if (isset($order['payment_type'])): ?>
                                <?php echo $order['payment_type']; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="col-sm-6 col-md-4">
                    <h6><strong>Shipping Information</strong></h6>

                    <div>
                        <small class="text-muted">Shipping method:</small>
                        <p>
                            <?php if (isset($order['shipping_service'])): ?>
                                <?php if ($order['shipping_service'] == 'shop/shipping/gateways/country'): ?>
                                    Shipping to country
                                <?php else: ?>
                                    <?php echo $order['shipping_service']; ?>
                                <?php endif; ?>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </p>
                    </div>

                    <div>
                        <small class="text-muted">Address:</small>
                        <p>
                            <?php
                            $zip = '';
                            if (isset($order['zip'])) {
                                $zip = $order['zip'];
                            }
                            ?>
                            <?php if (isset($order['country'])): ?><?php echo $order['country'] . ', '; ?><?php endif; ?>
                            <?php if (isset($order['state'])): ?><?php echo $order['state'] . ', '; ?><?php endif; ?>
                            <?php if (isset($order['city'])): ?><?php echo $order['city'] . ' ' . $zip . ', '; ?><?php endif; ?>
                            <?php if (isset($order['address'])): ?><?php echo $order['address']; ?><?php endif; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>