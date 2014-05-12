<?php App::import('Lib', 'Utils'); ?>
<?php if(isset($string)) : ?>
<div class="container">
    <h1><?php echo $string['title']; ?></h1>

    <?php if(isset($data) && (count($data) > 0)) : ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
                <?php foreach ($fields as $field => $fieldOptions): ?>
                    <th><?php echo $this->Paginator->sort($field, $string[$field]); ?></th>
                <?php endforeach; ?>
                <?php unset($fieldOptions); ?>
                <?php unset($field); ?>
                <th><?php echo $string['action']; ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 0; ?>
            <?php foreach ($data as $value): ?>
                <?php $count++; $mainModel = array_keys($value)[0]; ?>
                <tr>
                    <td><?php echo $this->Form->checkbox($mainModel . '.id.' . $value[$mainModel]['id']); ?></td>
                    <?php

                    foreach ($fields as $field => $fieldOptions) {

                        $pos = strpos($field, '.');
                        $model = $mainModel;

                        if($pos > 0) {
                            $model = substr($field, 0, $pos);
                            $field = substr($field, $pos + 1);
                        }

                        if(array_key_exists('center', $fieldOptions) && $fieldOptions['center'])
                            echo '<td class="text-center">';
                        else
                            echo '<td>';

                        if(array_key_exists('type', $fieldOptions)) {
                            switch ($fieldOptions['type']) {
                                case 'datetime':
                                    $text = $this->Time->niceShort($value[$model][$field]);
                                    break;
                                default:
                                    $text = $value[$model][$field];
                                    break;
                            }
                        } else {
                            $text = $value[$model][$field];
                        }

                        if(array_key_exists('link', $fieldOptions) && $fieldOptions['link']) {
                            echo $this->Html->link($text, array('action' => 'edit', $value[$model]['id']), array('escape' => false));
                        } else {
                            echo $text;
                        }

                        echo '</td>';
                    }
                    unset($fieldOptions);
                    unset($field);

                    echo '<td>';

                    foreach ($links as $link => $linkOptions) {

                        $urls = $linkOptions['url'];
                        for ($i = 0; $i < count($urls); $i++) {
                            if(array_key_exists($i, $urls) && Utils::endsWith($urls[$i], '()'))  {
                                $func = substr($urls[$i], 0, strlen($urls[$i]) - 2);
                                $urls[$i] = $func($value, $mainModel);
                            }

                        }

                        echo $this->Html->link($string[$link], $urls, $linkOptions['options']);

                        if($linkOptions !== end($links)) {
                            echo ' | ';
                        }
                    }
                    unset($linkOptions);
                    unset($link);

                    echo '</td>';
                    ?>
                </tr>
            <?php endforeach; ?>
            <?php unset($value); ?>
            </tbody>
        </table>

        <ul class="pagination pull-left">
            <?php
            echo $this->Paginator->first('《', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
            echo $this->Paginator->prev('〈', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
            echo $this->Paginator->numbers(array('tag' => 'li', 'separator' => '', 'currentClass' => 'active', 'currentTag' => 'a'));
            echo $this->Paginator->next('〉', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
            echo $this->Paginator->last('》', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
            ?>
        </ul>
    <?php else : ?>
        <?php echo $string['add.text']; ?>
    <?php endif; ?>
    <?php echo $this->Html->link($string['add.button'], array('action' => 'add'), array('class' => 'btn btn-default', 'style' => 'margin-left: 5px')); ?>
</div>
<?php endif; ?>