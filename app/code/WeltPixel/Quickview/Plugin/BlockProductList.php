<?php

namespace WeltPixel\Quickview\Plugin;

use Closure;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;

class BlockProductList
{
    const XML_PATH_QUICKVIEW_ENABLED = 'weltpixel_quickview/general/enable_product_listing';
    const XML_PATH_QUICKVIEW_BUTTONSTYLE = 'weltpixel_quickview/general/button_style';


    /**
     * @var UrlInterface
     */
    protected $urlInterface;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param UrlInterface $urlInterface
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        UrlInterface $urlInterface,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->urlInterface = $urlInterface;
        $this->scopeConfig = $scopeConfig;
    }

    public function aroundGetProductDetailsHtml(
        ListProduct $subject,
        Closure $proceed,
        Product $product
    )
    {
        $result = $proceed($product);
        $isEnabled = $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_ENABLED, ScopeInterface::SCOPE_STORE);
        if ($isEnabled) {
            $buttonStyle = 'weltpixel_quickview_button_' . $this->scopeConfig->getValue(self::XML_PATH_QUICKVIEW_BUTTONSTYLE, ScopeInterface::SCOPE_STORE);
            $productUrl = $this->urlInterface->getUrl('weltpixel_quickview/catalog_product/view', array('id' => $product->getId()));
            return $result . '<a class="weltpixel-quickview ' . $buttonStyle . '" data-quickview-url=' . $productUrl . ' href="javascript:void(0);"><span>' . __("Quickview") . '</span></a>';
        }

        return $result;
    }
}
