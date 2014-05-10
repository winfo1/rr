<div class="container">
    <h1><?php echo __('Verwaltung der Organisationseinheiten'); ?></h1>

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
        <?php foreach ($data as $organizationalunit): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Organizationalunit.id.' . $organizationalunit['Organizationalunit']['id']); ?></td>
                <td><?php echo $this->Html->link($organizationalunit['Organizationalunit']['short'], array('action' => 'edit', $organizationalunit['Organizationalunit']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $organizationalunit['Organizationalunit']['name']; ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($organizationalunit['Organizationalunit']['created']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($organizationalunit['Organizationalunit']['modified']); ?></td>
                <td><?php

                    echo $this->Html->link("Abonnieren", array('controller' => 'ical', 'action' => 'index', 'organizationalunit', $organizationalunit['Organizationalunit']['id']));

                    echo ' | ';

                    echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $organizationalunit['Organizationalunit']['id']));

                    echo ' | ';

                    echo $this->Html->link(__('Löschen'), array('action' => 'delete', $organizationalunit['Organizationalunit']['id']));

                    ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($organizationalunit); ?>
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