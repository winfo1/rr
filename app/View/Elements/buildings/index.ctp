<div class="container">
    <h1><?php echo __('Gebäudeverwaltung'); ?></h1>

    <?php if(isset($data) && (count($data) > 0)) : ?>
        <table class="table table-hover">
            <thead>
            <tr>
                <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
                <th><?php echo $this->Paginator->sort('short', 'Abkürzung'); ?>  </th>
                <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
                <th><?php echo $this->Paginator->sort('created', 'Erstellt'); ?></th>
                <th><?php echo $this->Paginator->sort('modified', 'Letzte Änderung'); ?></th>
                <th><?php echo __('Aktionen'); ?></th>
            </tr>
            </thead>
            <tbody>
            <?php $count = 0; ?>
            <?php foreach ($data as $value): ?>
                <?php $count++; ?>
                <tr>
                    <td><?php echo $this->Form->checkbox('Building.id.' . $value['Building']['id']); ?></td>
                    <td><?php echo $this->Html->link($value['Building']['short'], array('action' => 'edit', $value['Building']['id']), array('escape' => false)); ?></td>
                    <td class="text-center"><?php echo $value['Building']['name']; ?></td>
                    <td class="text-center"><?php echo $this->Time->niceShort($value['Building']['created']); ?></td>
                    <td class="text-center"><?php echo $this->Time->niceShort($value['Building']['modified']); ?></td>
                    <td><?php

                        echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $value['Building']['id']));

                        echo ' | ';

                        echo $this->Html->link(__('Löschen'), array('action' => 'delete', $value['Building']['id']));

                        ?></td>
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
        <?php echo __('Es existieren noch keine Gebäude. Jetzt das erste Gebäude'); ?>
    <?php endif; ?>
    <?php echo $this->Html->link(__('Hinzufügen'), array('action' => 'add'), array('class' => 'btn btn-default', 'style' => 'margin-left: 5px')); ?>
</div>