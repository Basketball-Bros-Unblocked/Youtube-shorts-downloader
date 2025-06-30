document.getElementById("downloadForm").addEventListener("submit", (e) => {
  e.preventDefault()
  const videoUrl = document.getElementById("videoUrl").value
  const resultDiv = document.getElementById("result")

  resultDiv.innerHTML = "Loading..."

  fetch("process.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: "url=" + encodeURIComponent(videoUrl),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        resultDiv.innerHTML = `<p style="color: red;">${data.error}</p>`
      } else {
        let html = `
                <h2>${data.title}</h2>
                <img src="${data.thumbnail}" alt="Video Thumbnail" style="max-width: 100%;">
                <table>
                    <tr>
                        <th>Format</th>
                        <th>Quality</th>
                        <th>Download</th>
                    </tr>
            `

        data.formats.forEach((format) => {
          html += `
                    <tr>
                        <td>${format.ext}</td>
                        <td>${format.format_note}</td>
                        <td><a href="download.php?url=${encodeURIComponent(videoUrl)}&format=${format.format_id}" target="_blank">Download</a></td>
                    </tr>
                `
        })

        html += "</table>"
        resultDiv.innerHTML = html
      }
    })
    .catch((error) => {
      resultDiv.innerHTML = `<p style="color: red;">An error occurred: ${error}</p>`
    })
})

