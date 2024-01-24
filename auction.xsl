<?xml version="1.0" encoding="utf-8"?>
<!-- Filename: auction.xsl
Author: Huaxing Zhang
ID: 102078766
Main function: XSL file to transform the XML file to a new format to display. -->
<!-- set up stylesheet version and output  -->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" indent="yes" version="4.0" doctype-public="-//W3C//DTD HTML 4.01//EN"
doctype-system="http://www.w3.org/TR/html4/strict.dtd"/>
<!-- set up xsl template in terms of status to be sold or failed -->
<xsl:template match="/items">        
    <xsl:variable name="soldItem" select="item[status = 'sold']" />
    <xsl:variable name="failItem" select="item[status = 'failed']" />
    <table class="reportTable" style="margin: 0 auto;">
        <xsl:if test="$soldItem or $failItem">
            <tr><th>Customer ID</th><th>Bidder ID</th><th>Item ID</th><th>Item Name</th><th>Category</th><th>Starting Price</th><th>Reserve Price</th><th>Buy It Now Price</th>
            <th>Sold Price</th><th>Bid Duration</th><th>Status</th><th>Current Date</th><th>Current Time</th><th>Revenue</th></tr>
        </xsl:if>            
        <xsl:apply-templates select="item[status = 'sold' or status = 'failed']"/>
    </table>
    <!-- output sold items, failed items and revenue -->
    <xsl:variable name="soldItems" select="count(item[status = 'sold'])"/>        
    <p style="font-size: 1.5rem; text-align: center;">Total Sold Items: <xsl:value-of select="$soldItems"/></p>
    <xsl:variable name="failItems" select="count(item[status = 'failed'])"/>
    <p style="font-size: 1.5rem; text-align: center;">Total Failed Items: <xsl:value-of select="$failItems"/></p>
    <xsl:variable name="revenue" select="sum(item[status = 'sold']/bidPrice) * 0.03 + sum(item[status = 'failed']/reservePrice) * 0.01"/>
    <p style="font-size: 1.5rem; text-align: center;">Total Revenue: <xsl:value-of select="$revenue"/></p>           
</xsl:template>
<!-- set up template to match item to create table format -->
<xsl:template match="item">    
    <tr>        
        <td class="bordered-cell"><xsl:value-of select="customerID"/></td>
        <td class="bordered-cell"><xsl:value-of select="bidderID"/></td>
        <td class="bordered-cell"><xsl:value-of select="itemID"/></td>
        <td class="bordered-cell"><xsl:value-of select="itemName"/></td>
        <td class="bordered-cell"><xsl:value-of select="category"/></td>
        <td class="bordered-cell"><xsl:value-of select="startingPrice"/></td>
        <td class="bordered-cell"><xsl:value-of select="reservePrice"/></td>
        <td class="bordered-cell"><xsl:value-of select="buyItNowPrice"/></td>
        <td class="bordered-cell"><xsl:value-of select="bidPrice"/></td>
        <td class="bordered-cell"><xsl:value-of select="duration"/></td>
        <td class="bordered-cell"><xsl:value-of select="status"/></td>
        <td class="bordered-cell"><xsl:value-of select="currentDate"/></td>
        <td class="bordered-cell"><xsl:value-of select="currentTime"/></td>
        <xsl:choose>
            <xsl:when test="status = 'sold'">                    
                <xsl:variable name="soldRevenue" select="bidPrice * 0.03"/>
                <td class="bordered-cell"><xsl:value-of select="$soldRevenue"/></td>
            </xsl:when>
            <xsl:when test="status = 'failed'">                   
                <xsl:variable name="failedRevenue" select="reservePrice * 0.01"/>
                <td class="bordered-cell"><xsl:value-of select="$failedRevenue"/></td>
            </xsl:when>
        </xsl:choose>            
    </tr>
    <br/>
</xsl:template>
</xsl:stylesheet>