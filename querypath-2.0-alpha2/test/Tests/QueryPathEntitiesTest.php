<?php
/**
 * Tests for the QueryPath library.
 * @package Tests
 * @author M Butcher <matt@aleph-null.tv>
 * @license The GNU Lesser GPL (LGPL) or an MIT-like license.
 */

require_once 'PHPUnit/Framework.php';
require_once '../src/QueryPath/QueryPath.php';

class QueryPathEntitiesTest extends PHPUnit_Framework_TestCase {
  public function testReplaceEntity() {
    $entity = 'amp';
    $this->assertEquals('38', QueryPathEntities::replaceEntity($entity));
    
    $entity = 'lceil';
    $this->assertEquals('8968', QueryPathEntities::replaceEntity($entity));
  }
  
  public function testReplaceAllEntities() {
    $test = '<?xml version="1.0"?><root>&amp;&copy;&#38;& nothing.</root>';
    $expect = '<?xml version="1.0"?><root>&#38;&#169;&#38;&#38; nothing.</root>';
    $this->assertEquals($expect, QueryPathEntities::replaceAllEntities($test));
    
    $test = '&&& ';
    $expect = '&#38;&#38;&#38; ';
    $this->assertEquals($expect, QueryPathEntities::replaceAllEntities($test));
  }
  
  public function testReplaceHexEntities() {
    $test = '&#xA9;';
    $expect = '&#xA9;';
    $this->assertEquals($expect, QueryPathEntities::replaceAllEntities($test));
  }
  
  public function testQPEntityReplacement() {
    $test = '<?xml version="1.0"?><root>&amp;&copy;&#38;& nothing.</root>';
    /*$expect = '<?xml version="1.0"?><root>&#38;&#169;&#38;&#38; nothing.</root>';*/
    // We get this because the DOM serializer re-converts entities.
    $expect = '<?xml version="1.0"?>
<root>&amp;&#xA9;&amp;&amp; nothing.</root>';
    
    $qp = qp($test, NULL, array('replace_entities' => TRUE));
    // Interestingly, the XML serializer converts decimal to hex and ampersands
    // to &amp;.
    $this->assertEquals($expect, trim($qp->xml()));
  }
}