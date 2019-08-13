<?php
class Bikeexchange_Produktexport_Model_Catalog_Product_Api extends Mage_Catalog_Model_Api_Resource
{

  protected $possiblebarcodefields = array('barcode', 'ean', 'upc', 'gtin', 'gtin-14', 'gtin14', 'gtin_14',
    'gtin-13', 'gtin13', 'gtin_13', 'gtin-12', 'gtin12', 'gtin_12', 'gtin-8', 'gtin8', 'gtin_8',
     'ucc-13', 'ucc13', 'ucc_13', 'ucc-12', 'ucc12', 'ucc_12', 'ucc-8', 'ucc8', 'ucc_8',
      'ean-8', 'ean8', 'ean_8', 'ean-13', 'ean13', 'ean_13', 'cip');

  protected $possiblebrandfields = array('brand', 'marke', 'hersteller', 'herst', 'manufacturer', 'manufac');

  protected $possiblecolorfields = array('color', 'farbe', 'farbton', 'farb_ton', 'farb-ton', 'colour', 'col');

  protected $possiblesizefields = array('size', 'größe', 'groesse', 'groeße', 'groese', 'grösse', 'gröse');

  protected $possiblegenderfields = array('gender', 'sex', 'geschlecht',
  'morf', 'form', 'f_m', 'm_f', 'f-m', 'm-f', 'f/m', 'm/f', 'm_or_f', 'f_or_m', 'm-or-f', 'f-or-m',
  'moderf', 'foderm', 'm_oder_f', 'f_oder_m', 'm-oder-f', 'f-oder-m',
  'morw', 'worm', 'w_m', 'm_w', 'w-m', 'm-w', 'w/m', 'm/w', 'm_or_w', 'w_or_m', 'm-or-w', 'w-or-m',
  'moderw', 'woderm', 'm_oder_w', 'w_oder_m', 'm-oder-w', 'w-oder-m',
   'malefemale', 'femalemale', 'male/female', 'female/male', 'male_female', 'male-female', 'male-female', 'female-male',
   'maleorfemale', 'femaleormale', 'male_or_female', 'female_or_male', 'male-or-female', 'female-or-male');

  protected $possibleyearfields = array('year', 'jahr', 'modelljahr', 'modell_jahr', 'modell-jahr', 'modelyear', 'model_year', 'model-year',
  'produktjahr', 'produkt_jahr', 'produkt-jahr', 'produktyear', 'produkt_year', 'produkt-year',
  'productjahr', 'product_jahr', 'product-jahr', 'productyear', 'product_year', 'product-year',
    'modeljahr', 'model_jahr', 'model-jahr', 'modellyear', 'modell_year', 'modell-year',
    'baujahr', 'serienjahr', 'serien_jahr', 'serien-jahr', 'serienyear', 'serien_year', 'serien-year',
     'serie', 'series', 'seriesyear', 'series_year', 'series-year', 'seriesjahr', 'series_jahr', 'series-jahr',
   'season', 'seasonyear', 'season_year', 'season-year', 'seasonjahr', 'season_jahr', 'season-jahr',
    'saison', 'saisonyear', 'saison_year', 'saison-year', 'saisonjahr', 'saison_jahr', 'saison-jahr');

  public function availabilityFeed($store = null)
  {
    $collection = Mage::getModel('catalog/product')->getCollection()
        ->setFlag('require_stock_items', true)
        ->addStoreFilter($this->_getStoreId($store))
        ->addAttributeToSelect('*');

    $result = array();
    foreach ($collection as $product)
     {
       $item = array(
            'product_id' => $product->getId(),
            'type_id'    => $product->getTypeId(),
            'sku'        => $product->getSku(),
            'status'     => $product->getStatus(),
            'visibility' => $product->getVisibility(),
            'price'      => $product->getPrice(),
            'special_price' => $product->getSpecialPrice(),
            'special_from_date' => $product->getSpecialFromDate(),
            'special_to_date' => $product->getSpecialToDate(),
            'tier_price' => $product->getTierPrice(),
            'qty'        => $product->getStockItem()->getQty(),
            'is_in_stock'=> $product->getStockItem()->getIsInStock()
        );
        foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute)
        {
          if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblebarcodefields) && !empty($product->getData($attribute->getAttributeCode())))
          {
            $item['barcode'] = $product->getData($attribute->getAttributeCode());
          }
        }
        $result[] = $item;
    }
    return $result;
  }

  public function productFeed($store = null)
  {
    $collection = Mage::getModel('catalog/product')->getCollection()
        ->setFlag('require_stock_items', true)
        ->addStoreFilter($this->_getStoreId($store))
        ->addAttributeToSelect('*');

        $result = array();
        foreach ($collection as $product)
         {
           $item = array(
                'product_id' => $product->getId(),
                'sku'        => $product->getSku(),
                'categories' => $product->getCategoryIds(),
                'websites'   => $product->getWebsiteIds(),
                'type_id'    => $product->getTypeId(),
                'name'       => $product->getName(),
                'description'=> $product->getDescription(),
                'short_description'=> $product->getShortDescription(),
                'status'     => $product->getStatus(),
                'product_url'   => $product->getProductUrl(),
                'visibility' => $product->getVisibility(),
                'price'      => $product->getPrice(),
                'special_price' => $product->getSpecialPrice(),
                'special_from_date' => $product->getSpecialFromDate(),
                'special_to_date' => $product->getSpecialToDate(),
                'tier_price' => $product->getTierPrice(),
                'meta_title' => $product->getData('meta_title'),
                'meta_description' => $product->getData('meta_description'),
                'qty'        => $product->getStockItem()->getQty(),
                'is_in_stock'=> $product->getStockItem()->getIsInStock(),
                'image_url'  => Mage::helper('catalog/product')->getImageUrl($product),
                'images'     => $product->getMediaGalleryImages()
            );
            $item['attributes'] = '';
            foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute)
            {
              $item['attributes'] .= $attribute->getAttributeCode().';';
              if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblebarcodefields))
              {
                if (!empty($product->getData($attribute->getAttributeCode())))
                {
                  $item['barcode'] = $product->getData($attribute->getAttributeCode());
                }
                else {
                  $item['barcode'] = 'empty';
                }
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblebrandfields))
              {
                if (!empty($product->getData($attribute->getAttributeCode())))
                {
                  $item['brand'] = $product->getData($attribute->getAttributeCode())
                  .'-'.$product->getAttributeText($attribute->getAttributeCode());
                }
                else {
                  $item['brand'] = 'empty';
                }
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblecolorfields))
              {
                if (!empty($product->getData($attribute->getAttributeCode())))
                {
                  $item['color'] = $product->getData($attribute->getAttributeCode())
                  .'-'.$product->getAttributeText($attribute->getAttributeCode());
                }
                else {
                  $item['color'] = 'empty';
                }
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblesizefields))
              {
                if (!empty($product->getData($attribute->getAttributeCode())))
                {
                  $item['size'] = $product->getData($attribute->getAttributeCode())
                  .'-'.$product->getAttributeText($attribute->getAttributeCode());
                }
                else {
                  $item['size'] = 'empty';
                }
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblegenderfields))
              {
                if (!empty($product->getData($attribute->getAttributeCode())))
                {
                  $item['gender'] = $product->getData($attribute->getAttributeCode())
                  .'-'.$product->getAttributeText($attribute->getAttributeCode());
                }
                else {
                  $item['gender'] = 'empty';
                }
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possibleyearfields))
              {
                if (!empty($product->getData($attribute->getAttributeCode())))
                {
                  $item['year'] = $product->getData($attribute->getAttributeCode())
                  .'-'.$product->getAttributeText($attribute->getAttributeCode());
                }
                else {
                  $item['year'] = 'empty';
                }
              }
            }
            $result[] = $item;
        }
        return $result;

  }

}
 ?>
