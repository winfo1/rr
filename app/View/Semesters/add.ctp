<?php setlocale(LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu'); ?>
<div class="container">
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
                $val = strftime('%d %B %Y');
                if (WIN)
                    $val = utf8_encode($val);
                echo $this->Form->input('startdate', array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => false,
                    'readonly' => true,
                    'value' => $val));
                ?>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <input type="hidden" name="data[Semester][start]" id="SemesterStart" value="<?php echo date('Y-m-d'); ?>"/>
        </div>
        <div class="form-group">
            <label for="SemesterEnddate"><?php echo __('Ende'); ?></label>

            <div class="input-group date form_end_date" data-date-format="dd MM yyyy" data-link-field="data[Semester][enddate]">
                <?php
                $val = strftime('%d %B %Y', strtotime("+3 months"));
                if (WIN)
                    $val = utf8_encode($val);
                echo $this->Form->input('enddate', array(
                    'type' => 'text',
                    'class' => 'form-control',
                    'label' => false,
                    'readonly' => true,
                    'value' => $val));
                ?>
                <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span></span>
                <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span>
            </div>
            <input type="hidden" name="data[Semester][end]" id="SemesterEnd" value="<?php echo date('Y-m-d', strtotime("+3 months")); ?>"/>
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