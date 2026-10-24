<?php
require_once "conexion.php";
$stats=[
'total'=>$pdo->query("SELECT COUNT(*) FROM productos")->fetchColumn(),
'vencidos'=>$pdo->query("SELECT COUNT(*) FROM productos WHERE fecha_vencimiento < CURDATE()")->fetchColumn(),
'hoy'=>$pdo->query("SELECT COUNT(*) FROM productos WHERE fecha_vencimiento = CURDATE()")->fetchColumn(),
'proximos'=>$pdo->query("SELECT COUNT(*) FROM productos WHERE fecha_vencimiento > CURDATE() AND DATEDIFF(fecha_vencimiento,CURDATE()) <= 7")->fetchColumn()
];
$productos=$pdo->query("SELECT *,DATEDIFF(fecha_vencimiento,CURDATE()) dias FROM productos ORDER BY fecha_vencimiento ASC,nombre ASC")->fetchAll();
function estado($d){if($d<0)return ['VENCIDO','danger'];if($d==0)return ['VENCE HOY','today'];if($d<=7)return ['1-7 DÍAS','warning'];if($d<=15)return ['8-15 DÍAS','soon'];return ['VIGENTE','success'];}
$alertas=array_values(array_filter($productos,fn($p)=>(int)$p['dias']<=15));
?><!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Control de Vencimientos</title><link rel="stylesheet" href="css/estilos.css"></head><body>
<header class="top"><div><div class="eyebrow">SISTEMA DE INVENTARIO</div><h1>Control de Vencimientos</h1><p>Supervisa fechas y detecta productos que requieren atención.</p></div><a class="btn primary" href="registrar.php">＋ Registrar producto</a></header>
<main class="container"><section class="cards">
<div class="card"><span>Total</span><strong><?=$stats['total']?></strong><small>Productos registrados</small></div>
<div class="card danger"><span>Vencidos</span><strong><?=$stats['vencidos']?></strong><small>Requieren atención</small></div>
<div class="card today"><span>Vencen hoy</span><strong><?=$stats['hoy']?></strong><small>Atención inmediata</small></div>
<div class="card warning"><span>Próximos 7 días</span><strong><?=$stats['proximos']?></strong><small>Prioridad alta</small></div></section>
<section class="attention"><div class="attention-title"><div><h2>⚠️ Atención</h2><p>Productos vencidos o con vencimiento dentro de los próximos 15 días.</p></div><span class="attention-count"><?=count($alertas)?></span></div>
<?php if($alertas):?><div class="alert-grid"><?php foreach(array_slice($alertas,0,8) as $p):[$txt,$cl]=estado((int)$p['dias']);?><div class="alert-item <?=$cl?>"><div><b><?=htmlspecialchars($p['nombre'])?></b><small><?=htmlspecialchars($p['codigo'])?> · <?=date('d/m/Y',strtotime($p['fecha_vencimiento']))?></small></div><span class="badge <?=$cl?>"><?=$p['dias']<0?abs($p['dias']).' d. vencido':($p['dias']==0?'Hoy':$p['dias'].' días')?></span></div><?php endforeach;?></div>
<?php else:?><div class="no-alert">✓ No hay productos que requieran atención.</div><?php endif;?></section>
<section class="panel"><div class="toolbar"><div><h2>Productos</h2><p>Los días y estados se calculan automáticamente.</p></div><input id="buscar" placeholder="🔎 Buscar por código o producto..."></div>
<div class="filters"><button class="filter active" data-filter="todos">Todos</button><button class="filter" data-filter="danger">🔴 Vencidos</button><button class="filter" data-filter="today">🚨 Hoy</button><button class="filter" data-filter="warning">🟠 1-7 días</button><button class="filter" data-filter="soon">🟡 8-15 días</button><button class="filter" data-filter="success">🟢 Vigentes</button></div>
<div class="tablewrap"><table id="tabla"><thead><tr><th>Código</th><th>Producto</th><th>Cantidad</th><th>Vencimiento</th><th>Días restantes</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
<?php foreach($productos as $p):[$txt,$cl]=estado((int)$p['dias']);?><tr data-search="<?=htmlspecialchars(strtolower($p['codigo'].' '.$p['nombre']))?>" data-status="<?=$cl?>"><td><b><?=htmlspecialchars($p['codigo'])?></b></td><td><?=htmlspecialchars($p['nombre'])?><br><small><?=htmlspecialchars($p['descripcion']??'')?></small></td><td><?=$p['cantidad']?></td><td><?=date('d/m/Y',strtotime($p['fecha_vencimiento']))?></td><td><b><?=$p['dias']<0?abs($p['dias']).' días vencido':($p['dias']==0?'Vence hoy':$p['dias'].' días')?></b></td><td><span class="badge <?=$cl?>"><?=$txt?></span></td><td><a class="action" href="editar.php?id=<?=$p['id']?>">Editar</a><a class="action delete" href="eliminar.php?id=<?=$p['id']?>" onclick="return confirm('¿Eliminar este producto?')">Eliminar</a></td></tr><?php endforeach;?>
<?php if(!$productos):?><tr><td colspan="7" class="empty">No hay productos registrados. ¡Agrega el primero!</td></tr><?php endif;?></tbody></table></div></section></main><script src="js/app.js"></script></body></html>