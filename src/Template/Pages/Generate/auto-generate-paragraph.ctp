<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Generate Paragraph</title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->css('home.css') ?>
    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">
</head>
<body class="home">

<header class="row">
    <div class="header-image">LawHQ CakePHP Test</div>
    <div class="header-title">
        <h1>Auto Generate paragraph</h1>
    </div>
</header>

<div class="row">
    <div class="columns large-6">
        <?php
            echo $this->Form->create(null, ['url'=> ['action' => 'generate']]);
            echo $this->Form->input('Plaintiffs', ['value' => 'Simon; Michael; Jack; John; Avery; Joseph']);
            echo $this->Form->input('Defendants', ['value' => 'Seal; Ted; May; Bolt; Henry; Moor']);
            echo $this->Form->input('DNCR', ['type'=>'checkbox']);
            echo $this->Form->input('IDNCL', ['type'=>'checkbox']);
            echo $this->Form->input('TIAA', ['type'=>'checkbox']);
            echo $this->Form->submit('Generate');
            echo $this->Form->end();
        ?>        
    </div>
    <div class="columns large-12">
        <?php 
            echo "<div class='result'>". $generated."</div>";
        ?>
    </div>
</div>

</body>
</html>
