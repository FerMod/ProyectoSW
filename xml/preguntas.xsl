<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<html>
			<head>
				<style>
					table, th, td {
					border-collapse: collapse;
					}

					th, td {
					text-align: left;
					padding: 8px;					
					border: 1px solid grey;
					}

					thead, tbody {
					border: 2px solid grey;
					}				

					tr:nth-child(even){
					background-color: #f2f2f2
					}
				</style>

				<h1> Preguntas realizadas </h1>

			</head>
			<body> 
				<div style="overflow-x: auto;">
					<table style="font-family: Verdana;">

						<thead>
							<tr>
								<th>Enunciado</th>
								<th>Complejidad</th>
								<th>Temática</th>
							</tr>
						</thead>

						<tbody>
							<xsl:for-each select="/assessmentItems/assessmentItem" >
								<tr>
									<td style="color: black;">
										<xsl:value-of select="itemBody"/> <br/>
									</td>
									<td style="color: green;">
										<xsl:value-of select="@complexity"/> <br/>
									</td>
									<td style="color: blue;">
										<xsl:value-of select="@subject"/> <br/>
									</td>
								</tr>
							</xsl:for-each>
						</tbody>

					</table>
				</div>

			</body>
		</html>
	</xsl:template>
</xsl:stylesheet> 
