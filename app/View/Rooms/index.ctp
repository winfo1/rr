<div class="container">
    <h1><?php echo __('Verwaltung der Räume'); ?></h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
            <th><?php echo $this->Paginator->sort('name', 'Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Organizationalunit.name', 'Organisationseinheit'); ?></th>
            <th><?php echo $this->Paginator->sort('Building.name', 'Gebäude'); ?></th>
            <th><?php echo $this->Paginator->sort('floor', 'Etage'); ?></th>
            <th><?php echo $this->Paginator->sort('number', 'Nummer'); ?></th>
            <th><?php echo $this->Paginator->sort('barrier_free', 'Barrierefrei'); ?></th>
            <th><?php echo $this->Paginator->sort('seats', 'Sitze'); ?></th>
            <th><?php echo $this->Paginator->sort('created', 'Erstellt'); ?></th>
            <th><?php echo $this->Paginator->sort('modified', 'Letzte Änderung'); ?></th>
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($rooms as $room): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Room.id.' . $room['Room']['id']); ?></td>
                <td><?php echo $this->Html->link($room['Room']['name'], array('action' => 'edit', $room['Room']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $room['Organizationalunit']['name']; ?></td>
                <td class="text-center"><?php echo $room['Building']['name']; ?></td>
                <td class="text-center"><?php echo $room['Room']['floor']; ?></td>
                <td class="text-center"><?php echo $room['Room']['number']; ?></td>
                <td class="text-center"><?php echo $room['Room']['barrier_free']; ?></td>
                <td class="text-center"><?php echo $room['Room']['seats']; ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($room['Room']['created']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($room['Room']['modified']); ?></td>
                <td><?php

                    echo $this->Html->link("Abonnieren", array('controller' => 'ical', 'action' => 'index', 'room', $room['Room']['id']));

                    echo ' | ';

                    echo $this->Html->link("Bearbeiten", array('action' => 'edit', $room['Room']['id']));

                    echo ' | ';

                    echo $this->Html->link("Löschen", array('action' => 'delete', $room['Room']['id']));

                    ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($room); ?>
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