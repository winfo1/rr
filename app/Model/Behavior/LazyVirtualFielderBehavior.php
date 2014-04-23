<?php
/**
 * Behavior to stop CakePHP from including virtual fields unless they're
 * explicitly requested in the fields array.
 *
 * To find out more about virtual fields, consult the documentation in the
 * manual here:
 *
 * http://book.cakephp.org/view/1608/Virtual-fields
 *
 * @author joe bartlett (xo@jdbartlett.com)
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @package LazyVirtualFielderBehavior
 */
class LazyVirtualFielderBehavior extends ModelBehavior {

    /**
     * After find callback. Wipes the model's virtual fields array for the next
     * query.
     *
     * @param object $model Model using this behavior
     * @access public
     */
    public function afterFind(Model $model, $results, $primary = false) {
        $model->virtualFields = array();
    }

    /**
     * Runs before a Model::find() operation. Examines the query's requested
     * fields and tailors the model's ::virtualFields property appropriately.
     *
     * @param object $Model Model using this behavior
     * @param array $query Query parameters
     * @return bool TRUE
     * @access public
     */
    public function beforeFind(Model $model, $query) {
        if (empty($model->lazyVirtualFields)) {
            $model->lazyVirtualFields = $model->virtualFields;
        }

        if (empty($model->lazyVirtualFields)) {
            return TRUE;
        }

        $model->virtualFields = array();

        $fields = (array)$query['fields'];
        foreach ($fields as $field) {
            if ($lazyVirtualField = $this->getLazyVirtualField($model, $field)) {
                $model->virtualFields += $lazyVirtualField;
            }
        }

        return TRUE;
    }

    /**
     * Given a model and field name, returns an array representing a single
     * virtual field if one is found.
     *
     * @param object $Model Model using this behavior
     * @param string $field Name of a field being requested
     * @return mixed Boolean FALSE if nothing is found, array otherwise
     * @access public
     */
    public function getLazyVirtualField(&$Model, $field) {
        if (empty($Model->lazyVirtualFields) || !is_string($field)) {
            return FALSE;
        }

        if (isset($Model->lazyVirtualFields[$field])) {
            return array($field => $Model->lazyVirtualFields[$field]);
        }

        if (strpos($field, '.') !== FALSE) {
            list($model, $field) = explode('.', $field);
            if ($model == $Model->alias && isset($Model->lazyVirtualFields[$field])) {
                return array($field => $Model->lazyVirtualFields[$field]);
            }
        }

        return FALSE;
    }

}