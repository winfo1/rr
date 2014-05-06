<div class="container">
    <?php echo $this->Form->create('Room', array(
        'url' => array_merge(array('action' => 'find'), $this->params['pass']),
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Raum suchen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten des zu suchenden Raumes an'); ?></legend>

        <?php echo $this->Form->input('name', array('label' => __('Name'), 'placeholder' => __('Name'))); ?>

        <?php echo $this->Form->input('organizationalunit_id', array('label' => __('Organisationseinheit'), 'options' => $organizationalunits, 'empty' => __('(Bitte auswählen)'))); ?>

        <?php echo $this->Form->input('building_id', array('label' => __('Gebäude'), 'options' => $buildings, 'empty' => __('(Bitte auswählen)'))); ?>

        <?php echo $this->Form->input('floor', array('label' => __('Etage'), 'placeholder' => __('Etage'))); ?>

        <?php echo $this->Form->input('number', array('label' => __('Nummer'), 'placeholder' => __('Nummer'))); ?>

        <?php echo $this->Form->input('barrier_free', array('label' => __('Barrierefrei'), 'options' => array('1' => 'ja', '0' => 'nein'), 'empty' => __('(Bitte auswählen)'))); ?>

        <?php echo $this->Form->input('seats', array('label' => __('Sitze'), 'placeholder' => __('Sitze'))); ?>

        <?php // echo $this->Form->input('Resource.0.id', array('label' => __('Ressourcen'), 'options' => $resources, 'empty' => __('(Bitte auswählen)'))); ?>

    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Suchen'), 'class' => 'btn btn-primary btn-lg')); ?>

    <h1><?php echo __('Suchergebnis'); ?></h1>

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
            <th><?php echo __('Aktionen'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php $count = 0; ?>
        <?php foreach ($rooms as $room): ?>
            <?php $count++; ?>
            <tr>
                <td><?php echo $this->Form->checkbox('Room.id.' . $room['Room']['id']); ?></td>
                <td><?php echo $this->Html->link($room['Room']['name'], array('controller' => 'bookings', 'action' => 'add', $room['Room']['id']), array('escape' => false)); ?></td>
                <td class="text-center"><?php echo $room['Organizationalunit']['name']; ?></td>
                <td class="text-center"><?php echo $room['Building']['name']; ?></td>
                <td class="text-center"><?php echo $room['Room']['floor']; ?></td>
                <td class="text-center"><?php echo $room['Room']['number']; ?></td>
                <td class="text-center"><?php echo $room['Room']['barrier_free']; ?></td>
                <td class="text-center"><?php echo $room['Room']['seats']; ?></td>
                <td><?php echo $this->Html->link("Buchen", array('controller' => 'bookings', 'action' => 'add', $room['Room']['id'])); ?></td>
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
</div>