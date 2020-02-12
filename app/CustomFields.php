<?php

namespace UKRI\Search;

class CustomFields implements \Dxw\Iguana\Registerable
{
    private $fieldMapping;
    
    public function __construct(FieldMapping $fieldMapping)
    {
        $this->fieldMapping = $fieldMapping;
    }
    
    public function register()
    {
        add_filter('ep_post_sync_args_post_prepare_meta', [$this, 'addCustomFieldsToContent'], 10, 1);
    }
    
    public function addCustomFieldsToContent($document)
    {
        $mappedFields = $this->documentMappedFields($document);
        
        if (!$mappedFields) {
            return $document;
        }
        
        $content = $document['post_content'];
        
        foreach ($mappedFields as $field) {
            $content .= ' ' . get_field($field, $document['post_id']);
        }
        
        $document['post_content'] = $content;
        
        return $document;
    }
    
    private function documentMappedFields($document)
    {
        $fieldMap = $this->fieldMapping->get();
        $postType = $document['post_type'];
        
        if (!array_key_exists($postType, $fieldMap)) {
            return false;
        }
        
        $templateName = $this->getTemplateName($document['post_id']);
        
        if (!array_key_exists($templateName, $fieldMap[$postType])) {
            return false;
        }
        
        return $fieldMap[$postType][$templateName];
    }
    
    
    private function getTemplateName($postId)
    {
        $slug = get_page_template_slug($postId);
        if ($slug == '') {
            return 'default';
        } else {
            return pathinfo($slug, PATHINFO_FILENAME);
        }
    }
}
