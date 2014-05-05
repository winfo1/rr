<div class="container">
    <h1><?php echo __('Semesterverwaltung'); ?></h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
            <th><?php echo $this->Paginator->sort('short', 'Abkürzung'); ?>  </th>
            <th><?php echo $this->Paginator->sort('start', 'Start'); ?></th>
            <th><?php echo $this->Paginator->sort('end', 'Ende'); ?></th>
            <th><?php echo $this->Paginator->sort('created', 'Erstellt'); ?></th>
            <th><?php echo $this->Paginator->sort('modified', 'Letzte Änderung'); ?></th>
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($semesters as $semester): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Semester.id.' . $semester['Semester']['id']); ?></td>
                <td><?php echo $this->Html->link($semester['Semester']['short'], array('action' => 'edit', $semester['Semester']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($semester['Semester']['start']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($semester['Semester']['end']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($semester['Semester']['created']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($semester['Semester']['modified']); ?></td>
                <td><?php

                    echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $semester['Semester']['id']));

                    echo ' | ';

                    echo $this->Html->link(__('Löschen'), array('action' => 'delete', $semester['Semester']['id']));

                    ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($semester); ?>
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