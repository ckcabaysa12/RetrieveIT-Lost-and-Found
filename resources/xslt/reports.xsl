<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <html lang="en">
            <head>
                <meta charset="UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1"/>
                <title>RetrieveIT Report (XSLT)</title>
                <style>
                    body { font-family: 'Segoe UI', sans-serif; background: #f6f8f7; color: #1f2933; margin: 0; padding: 2rem; }
                    .wrap { max-width: 960px; margin: 0 auto; }
                    h1 { color: #2d6a6a; margin-bottom: .25rem; }
                    .meta { color: #64748b; margin-bottom: 1.5rem; }
                    .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
                    .card { background: #fff; border: 1px solid rgba(45,106,106,.08); border-radius: 16px; padding: 1rem 1.25rem; box-shadow: 0 2px 16px rgba(45,106,106,.07); }
                    .card h2 { font-size: 1rem; color: #2d6a6a; margin: 0 0 .75rem; }
                    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 16px; overflow: hidden; }
                    th, td { padding: .75rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
                    th { background: #e8f4f4; color: #1e4848; }
                    .stat { display: flex; justify-content: space-between; padding: .35rem 0; }
                    .badge { background: #e8f4f4; color: #2d6a6a; padding: .2rem .55rem; border-radius: 999px; font-size: .8rem; }
                </style>
            </head>
            <body>
                <div class="wrap">
                    <h1>RetrieveIT System Report</h1>
                    <p class="meta">
                        Generated: <xsl:value-of select="/retrieveit-report/@generated-at"/>
                        · Transformed with XSLT
                    </p>

                    <div class="grid">
                        <div class="card">
                            <h2>Users</h2>
                            <div class="stat"><span>Total</span><strong><xsl:value-of select="/retrieveit-report/users/total"/></strong></div>
                            <div class="stat"><span>Verified</span><strong><xsl:value-of select="/retrieveit-report/users/verified"/></strong></div>
                            <div class="stat"><span>Pending ID</span><strong><xsl:value-of select="/retrieveit-report/users/pending"/></strong></div>
                        </div>
                        <div class="card">
                            <h2>Items</h2>
                            <div class="stat"><span>Lost</span><strong><xsl:value-of select="/retrieveit-report/items/lost"/></strong></div>
                            <div class="stat"><span>Found</span><strong><xsl:value-of select="/retrieveit-report/items/found"/></strong></div>
                            <div class="stat"><span>Returned</span><strong><xsl:value-of select="/retrieveit-report/items/returned"/></strong></div>
                            <div class="stat"><span>Pending claim</span><strong><xsl:value-of select="/retrieveit-report/items/pending_claim"/></strong></div>
                        </div>
                        <div class="card">
                            <h2>Claims</h2>
                            <div class="stat"><span>Pending</span><strong><xsl:value-of select="/retrieveit-report/claims/pending"/></strong></div>
                            <div class="stat"><span>Approved</span><strong><xsl:value-of select="/retrieveit-report/claims/approved"/></strong></div>
                            <div class="stat"><span>Rejected</span><strong><xsl:value-of select="/retrieveit-report/claims/rejected"/></strong></div>
                        </div>
                    </div>

                    <div class="card">
                        <h2>Items by Category</h2>
                        <table>
                            <thead>
                                <tr><th>Category</th><th>Count</th></tr>
                            </thead>
                            <tbody>
                                <xsl:for-each select="/retrieveit-report/categories/category">
                                    <tr>
                                        <td><xsl:value-of select="@name"/></td>
                                        <td><span class="badge"><xsl:value-of select="@count"/></span></td>
                                    </tr>
                                </xsl:for-each>
                            </tbody>
                        </table>
                    </div>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
