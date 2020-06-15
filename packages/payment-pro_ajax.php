<?php
if(function_exists('payment_pro_cart_add') && Params::getParam('pck')!='') {
    $pck = Params::getParam('pck');
    if(!Packages::newInstance()->isEnabled($pck)) {
        echo json_encode(array('error' => 1, 'msg' => __('This package is not enabled yet', 'packages')));
        die;
    }

    $package = Packages::newInstance()->getPackageById($pck);

    if(isset($package['b_active']) && $package['b_active']==true) {
        payment_pro_cart_add('PCK' . $package['pk_i_id'] . '-' . $package['pk_i_id'], sprintf(__('Package: %d', 'packages'), $package['s_name']), $package['i_price'], 1, osc_get_preference('default_tax', 'payment_pro'));
        echo json_encode(array('error' => 0, 'msg' => __('Product added to your cart', 'payment_pro')));
        die;
    }

    echo json_encode(array('error' => 1, 'msg' => __('This packages does not belong to you', 'packages')));
    die;
}
