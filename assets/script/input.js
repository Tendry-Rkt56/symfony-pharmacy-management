const limit = document.getElementById('limit')

if (limit) {
     limit.addEventListener('input', (e) => {
          if (e.target.value < 1) e.target.value = 1
     })
}