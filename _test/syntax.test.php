<?php
/**
 * @group plugin_abstract
 * @group plugins
 */
class plugin_abstract_syntax_test extends DokuWikiTest {

    public function setup() {
        $this->pluginsEnabled[] = 'abstract';
        parent::setup();
    }
    
    public function test_basic_syntax() {
        global $INFO;
        $INFO['id'] = 'test:plugin_abstract:syntax';
        saveWikiText('test:plugin_abstract:syntax','<abstract>Test</abstract>','test');
        
        $xhtml = p_wiki_xhtml('test:plugin_abstract:syntax');
        $meta = p_get_metadata('test:plugin_abstract:syntax','description');
    
        $doc = phpQuery::newDocument($xhtml);
        $text = trim(pq("p",$doc)->eq(0)->text());
        
        $this->assertEquals('Test', $text);
        $this->assertEquals('Test', $meta['abstract']);
    }
    
    public function test_basic_syntax_hide() {
        global $INFO;
        $INFO['id'] = 'test:plugin_abstract:syntax';
        saveWikiText('test:plugin_abstract:syntax','<abstract hide>Test</abstract>','test');
        
        $xhtml = p_wiki_xhtml('test:plugin_abstract:syntax');
        $meta = p_get_metadata('test:plugin_abstract:syntax','description');
        
        $doc = phpQuery::newDocument($xhtml);
        $text = trim(pq("p",$doc)->eq(0)->text());
        
        $this->assertEquals('Test', $text);
        $this->assertEquals('Test', $meta['abstract']);
    }
}
