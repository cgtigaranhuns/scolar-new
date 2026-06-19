<!DOCTYPE html>
<html>
<head>
	<title>Liberação - Conselho</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			line-height: 1.6;
			margin: 0;
			padding: 20px;
		}
		.container {
			max-width: 800px;
			margin: 0 auto;
			background-color: #f8fff8;
			padding: 30px;
			border-radius: 8px;
			box-shadow: 0 0 10px rgba(0,0,0,0.1);
		}
		.header {
			text-align: center;
			margin-bottom: 20px;
			color: #2e7d32;
		}
		.content {
			background-color: #ffffff;
			padding: 20px;
			border-radius: 5px;
			border: 1px solid #e0f2e0;
		}
		p {
			color: #727171;
		}	
		
	</style>
</head>
<body>
	<div style="text-align: center; margin-bottom: 20px;">
		<img src="{{ asset('https://extensao.garanhuns.ifpe.edu.br/img/Logo-Garanhuns.png') }}" alt="Logo" style="max-width: 200px;">
	</div>
	<div class="container">
		<div class="header">
			<h2>Liberação do Conselho de Classe</h2>			
		</div>

		<div class="content">
			<p>Prezado(a) professor(a),</p>

			<p>As avaliações do Conselho <strong>{{ $conselho->descricao }}</strong>, já estão disponíveis para serem respondidas.</p>

			<p>Segue o link: <a href="http://scolar.garanhuns.ifpe.edu.br/">http://scolar.garanhuns.ifpe.edu.br/</a></p>

			<p>Realize o preenchimento da avaliação no prazo estabelecido de <strong>{{ optional($conselho->data_inicio) ? \Carbon\Carbon::parse($conselho->data_inicio)->format('d/m/Y') : 'xx' }} a {{ optional($conselho->data_fim) ? \Carbon\Carbon::parse($conselho->data_fim)->format('d/m/Y') : 'xx' }}</strong></p>

			<p><strong>OBS.:</strong> Este email foi enviado automaticamente pelo sistema, não é necessário responder.</p>
		</div>
	</div>
</body>
</html>
