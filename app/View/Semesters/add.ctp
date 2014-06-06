<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<div class="container">
    <ol class="breadcrumb well well-sm">
        <li><?php echo __('Verwaltung'); ?></li>
        <li><?php echo $this->Html->link('Semester', array('action' => 'index')); ?></li>
        <li class="active"><?php echo __('Semester hinzufügen'); ?></li>
    </ol>
    <?php echo $this->Form->create('Semester', array(
        'class' => 'well',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
        )
    )); ?>
    <h1><?php echo __('Semester hinzufügen'); ?></h1>
    <fieldset>
        <legend><?php echo __('Geben Sie hier die Daten des neuen Semesters an'); ?></legend>

        <?php echo $this->Form->input('short', array('label' => __('Abkürzung'), 'placeholder' => __('Abkürzung z.B. WS15/16 oder SS16'))); ?>

        <div class="form-group">
            <label for="SemesterStartdate"><?php echo __('Start'); ?></label>

            <div class="input-group date form_start_date" data-date-format="dd MM yyyy" data-link-field="data[Semester][startdate]">
                <?php
                $default_start_date = strtotime("now");
                echo $this->Form->input('startdate', array(
                    'type' => 'text',
                    'div' => false,
                    'label' => false,
                    'readonly' => true,
                    'value' => $this->mytime->toReadableDate($default_start_date, true)));
                ?>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <?php
            if ($this->Form->isFieldError('start')) {
                echo $this->Form->error('start');
            }
            echo $this->Form->hidden('start', array('value' => date('Y-m-d', $default_start_date)));
            ?>
        </div>
        <div class="form-group">
            <label for="SemesterEnddate"><?php echo __('Ende'); ?></label>

            <div class="input-group date form_end_date" data-date-format="dd MM yyyy" data-link-field="data[Semester][enddate]">
                <?php
                $default_end_date = strtotime("+3 months");
                echo $this->Form->input('enddate', array(
                    'type' => 'text',
                    'div' => false,
                    'label' => false,
                    'readonly' => true,
                    'value' => $this->mytime->toReadableDate($default_end_date, true)));
                ?>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <?php
            if ($this->Form->isFieldError('end')) {
                echo $this->Form->error('end');
            }
            echo $this->Form->hidden('end', array('value' => date('Y-m-d', $default_end_date)));
            ?>
        </div>
    </fieldset>
    <?php echo $this->Form->end(array('label' => __('Hinzufügen'), 'class' => 'btn btn-primary btn-lg')); ?>
</div>

    <script type="text/javascript">
        $('#SemesterShort').focus();

        $('.form_start_date').datetimepicker({
            language: 'de',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: 'bottom-left',
            linkField: "SemesterStart",
            linkFormat: "yyyy-mm-dd"
        });

        $('.form_end_date').datetimepicker({
            language: 'de',
            weekStart: 1,
            todayBtn: 1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            forceParse: 0,
            pickerPosition: 'bottom-left',
            linkField: "SemesterEnd",
            linkFormat: "yyyy-mm-dd"
        });
    </script>