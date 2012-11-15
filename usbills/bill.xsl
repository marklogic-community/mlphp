<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" />
    <xsl:template match="/">
        <div id="bill-body">
        <div id="bill-title"><strong><xsl:value-of select="bill/@abbrev"/>: <xsl:value-of select="bill/title"/></strong></div>
        <div class="bill-details">
            <span class="bill-detail"><strong>Status: </strong> <xsl:value-of select="bill/status"/></span>
            <span class="bill-detail"><strong>Introduced: </strong><xsl:value-of select="bill/introduced/@date"/></span>
            <span class="bill-detail"><strong>Subject(s): </strong>
                <xsl:for-each select="bill/subjects">
                    <xsl:value-of select="subject"/>
                    <xsl:if test="position() &lt; last()">,</xsl:if>
                </xsl:for-each>
            </span>
        </div>
        <div class="bill-details"><div><strong>Summary: </strong></div></div>
        <div id="bill-summary"><xsl:value-of select="bill/summary"/></div>
        <div class="bill-details">
            <span class="bill-detail"><a>
                <xsl:attribute name="target">_blank</xsl:attribute>
                <xsl:attribute name="href"><xsl:value-of select="bill/link/@href" /></xsl:attribute>
                View Full Text on THOMAS
            </a></span>
        </div>
        </div>
    </xsl:template>
</xsl:stylesheet>