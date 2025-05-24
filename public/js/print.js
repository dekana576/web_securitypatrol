  function printTable() {
    const tableContent = document.getElementById("example").outerHTML;

    const printWindow = window.open("", "", "width=800,height=600");
    printWindow.document.write(`
      <html>
        <head>
          <title>Cetak Tabel</title>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
          <style>
            body {
              padding: 20px;
            }
            table {
              width: 100%;
              border-collapse: collapse;
            }
            th, td {
              border: 1px solid #dee2e6;
              padding: 8px;
              text-align: left;
            }
          </style>
        </head>
        <body>
          <h4>Data Tabel</h4>
          ${tableContent}
        </body>
      </html>
    `);
    printWindow.document.close();
    printWindow.print();
  }


