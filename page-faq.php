<?php
/** FAQ (slug faq): numbered groups, accordion questions (pages.js). */
get_header();
$qa = [
    ['When do I receive my order?', 'When placing the order, a day of shipment is indicated. After the order has been placed, the same delivery time will also be stated on the order confirmation. It is therefore never possible that during the order, the shipping day on the website, is different than on the order confirmation.'],
    ['I now see the longer delivery time of (a part of) my order. How can I cancel it?', 'If the order has a longer delivery time than you had previously seen, it is of course possible to cancel (a part of) the order. For this you can contact our customer service. They will cancel the order for you. The purchase amount will be back on your bank account within two working days. When an order has already been shipped, it can no longer be cancelled.'],
    ['When will I receive the invoice for my order?', 'When you have paid for the order, you will not automatically receive an invoice for your order. If you wish to receive an invoice, this can be done in two ways.The first way is through your account at our store. When you log in to your account you can see your orders and download the invoice.'],
];
$groups = ['01. The order', '02. Shipment', '03. The order', '04. Returns, exchanges and complaints'];
?>
<main class="container mt-18 pb-25 min-[1200px]:mt-29.5" data-faq>
    <div class="pt-14 pb-12.5 text-center">
        <?php kidara_icon_e('circle-help', 'text-[40px] text-heading'); ?>
        <h1 class="mt-6 mb-0 font-body text-[40px] leading-[1.2] text-heading min-[768px]:text-[50px]">Frequently Asked Questions.</h1>
    </div>
    <div class="-mx-3.75 flex flex-wrap">
        <?php foreach ($groups as $gi => $g) : ?>
            <div class="mb-12.5 w-full px-3.75 min-[768px]:w-1/2">
                <h2 class="mb-7.5 mt-0 font-body text-[30px] leading-[1.2] text-heading"><?= esc_html($g) ?></h2>
                <?php foreach ($qa as $qi => $item) : $id = "faq-$gi-$qi"; ?>
                    <div class="mb-5 js-faq-item">
                        <h3 class="m-0"><button type="button" class="js-faq-q flex w-full cursor-pointer items-center justify-between gap-3 bg-transparent p-0 text-start font-body text-base font-normal leading-[1.4] text-body" aria-expanded="true" aria-controls="<?= $id ?>"><span><?= esc_html($item[0]) ?></span><?php kidara_icon_e('chevron-down', 'text-[16px] shrink-0 transition-transform duration-200 [.is-closed_&]:-rotate-90'); ?></button></h3>
                        <div id="<?= $id ?>" class="js-faq-a pt-2.5 text-sm leading-6"><p class="m-0"><?= esc_html($item[1]) ?></p></div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>
<?php get_footer();
