<div class="container">
    <h1><?php echo __('Ressourcenverwaltung'); ?></h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
            <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
            <th><?php echo $this->Paginator->sort('type', 'Typ'); ?></th>
            <th><?php echo $this->Paginator->sort('created', 'Erstellt'); ?></th>
            <th><?php echo $this->Paginator->sort('modified', 'Letzte Änderung'); ?></th>
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($data as $resource): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Resource.id.' . $resource['Resource']['id']); ?></td>
                <td><?php echo $this->Html->link($resource['Resource']['name'], array('action' => 'edit', $resource['Resource']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $type[$resource['Resource']['type']]; ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($resource['Resource']['created']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($resource['Resource']['modified']); ?></td>
                <td><?php

                    echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $resource['Resource']['id']));

                    echo ' | ';

                    echo $this->Html->link(__('Löschen'), array('action' => 'delete', $resource['Resource']['id']));

                    ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($resource); ?>
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

    <?php
    echo $this->Html->link(__('Hinzufügen'), array('action' => 'add'), array('class' => 'btn btn-default', 'style' => 'margin-left: 5px'));
    ?>
</div>