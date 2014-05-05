<div class="container">
    <h1><?php echo __('Verwaltung der Benutzer'); ?></h1>

    <table class="table table-hover">
        <thead>
        <tr>
            <th><?php echo $this->Form->checkbox('all', array('name' => 'CheckAll', 'id' => 'CheckAll')); ?></th>
            <th><?php echo $this->Paginator->sort('username', 'Name'); ?></th>
            <th><?php echo $this->Paginator->sort('Organizationalunit.name', 'Organisationseinheit'); ?></th>
            <th><?php echo $this->Paginator->sort('emailaddress', 'E-Mail-Adresse'); ?></th>
            <th><?php echo $this->Paginator->sort('created', 'Erstellt'); ?></th>
            <th><?php echo $this->Paginator->sort('modified', 'Letzte Änderung'); ?></th>
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($users as $user): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('User.id.' . $user['User']['id']); ?></td>
                <td><?php echo $this->Html->link($user['User']['username'], array('action' => 'edit', $user['User']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $user['Organizationalunit']['name']; ?></td>
                <td class="text-center"><?php echo $user['User']['emailaddress']; ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($user['User']['created']); ?></td>
                <td class="text-center"><?php echo $this->Time->niceShort($user['User']['modified']); ?></td>
                <td><?php

                    if($user['User']['organizationalunit_fixed'] && !$user['User']['organizationalunit_verified'] && ($user['User']['role'] == 'user')) {

                        echo $this->Html->link("Freigeben", array('action' => 'do_upgrade', $user['User']['id']));

                        echo ' | ';
                    }

                    echo $this->Html->link(__('Bearbeiten'), array('action' => 'edit', $user['User']['id']));

                    echo ' | ';

                    echo $this->Html->link(__('Löschen'), array('action' => 'delete', $user['User']['id']));

                    ?></td>
            </tr>
        <?php endforeach; ?>
        <?php unset($user); ?>
        </tbody>
    </table>

    <ul class="pagination pull-left">
        <?php
        echo $this->Paginator->prev('«', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
        echo $this->Paginator->numbers(array('tag' => 'li', 'separator' => '', 'currentClass' => 'active', 'currentTag' => 'a'));
        echo $this->Paginator->next('»', array('class' => '', 'tag' => 'li'), null, array('class' => 'disabled', 'tag' => 'li'));
        ?>
    </ul>

    <?php
    echo $this->Html->link(__('Hinzufügen'), array('action' => 'add'), array('class' => 'btn btn-default', 'style' => 'margin-left: 5px'));
    ?>
</div>