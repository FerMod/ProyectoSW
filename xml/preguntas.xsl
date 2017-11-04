<?xml version="1.0" ?>
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:template match="/">
		<html> 

			<body> 
				<p> <h1> Preguntas realizadas </h1> </p>
				<table border="1">
					<thead>
						<tr> <th>Enunciado</th> <th>Complejidad</th> <th>Temática</th> </tr>
					</thead>
					<xsl:for-each select="/assessmentItems/assessmentItem" >
						<tr>

							<td>
								<font size="2" color="black" face="Verdana">
									<xsl:value-of select="itemBody"/> <br/>
								</font>
							</td>

							<td>
								<font size="2" color="green" face="Verdana">
									<xsl:value-of select="@complexity"/> <br/>
								</font>
							</td>

							<td>
								<font size="2" color="blue" face="Verdana">
									<xsl:value-of select="@subject"/> <br/>
								</font>
							</td>

						</tr>
					</xsl:for-each>
				</table>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet> 
