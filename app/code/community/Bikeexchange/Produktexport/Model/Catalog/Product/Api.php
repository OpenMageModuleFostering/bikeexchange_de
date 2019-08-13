<?php
class Bikeexchange_Produktexport_Model_Catalog_Product_Api extends Mage_Catalog_Model_Api_Resource
{

  protected $possiblebarcodefields = array('barcode', 'ean', 'upc', 'gtin', 'gtin-14', 'gtin-13', 'gtin-12', 'gtin-8', 'ucc-13', 'ucc-12', 'ucc-8', 'ean-8', 'ean-13', 'cip');
  protected $possiblebrandfields = array('brand', 'marke', 'hersteller', 'manufacturer');
  protected $possiblecolorfields = array('color', 'farbe', 'colour');
  protected $possiblesizefields = array('size', 'größe', 'groesse');
  protected $possiblegenderfields = array('gender', 'sex', 'geschlecht', 'gendered');
  protected $possibleyearfields = array('year', 'jahr', 'modelljahr', 'modelyear', 'produktjahr');

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
          else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblebrandfields) && !empty($product->getData($attribute->getAttributeCode())))
          {
            $item['brand'] = $product->getData($attribute->getAttributeCode());
          }
          else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblecolorfields) && !empty($product->getData($attribute->getAttributeCode())))
          {
            $item['color'] = $product->getData($attribute->getAttributeCode());
          }
          else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblesizefields) && !empty($product->getData($attribute->getAttributeCode())))
          {
            $item['size'] = $product->getData($attribute->getAttributeCode());
          }
          else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblegenderfields) && !empty($product->getData($attribute->getAttributeCode())))
          {
            $item['gender'] = $product->getData($attribute->getAttributeCode());
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
            foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute)
            {
              if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblebarcodefields) && !empty($product->getData($attribute->getAttributeCode())))
              {
                $item['barcode'] = $product->getAttributeText($attribute->getAttributeCode());
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblebrandfields) && !empty($product->getData($attribute->getAttributeCode())))
              {
                $item['brand'] = $product->getAttributeText($attribute->getAttributeCode());
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblecolorfields) && !empty($product->getData($attribute->getAttributeCode())))
              {
                $item['color'] = $product->getAttributeText($attribute->getAttributeCode());
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblesizefields) && !empty($product->getData($attribute->getAttributeCode())))
              {
                $item['size'] = $product->getAttributeText($attribute->getAttributeCode());
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possiblegenderfields) && !empty($product->getData($attribute->getAttributeCode())))
              {
                $item['gender'] = $product->getAttributeText($attribute->getAttributeCode());
              }
              else if (in_array(strtolower($attribute->getAttributeCode()), $this->possibleyearfields) && !empty($product->getData($attribute->getAttributeCode())))
              {
                $item['year'] = $product->getAttributeText($attribute->getAttributeCode());
              }
            }
            $result[] = $item;
        }
        return $result;

  }

}
 ?>
