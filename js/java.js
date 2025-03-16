
   
    

    document.addEventListener("DOMContentLoaded", function () {
        let links = document.querySelectorAll(".opciones a");
        let currentUrl = window.location.pathname.split("/").pop(); // Obtiene el nombre del archivo actual
    
        links.forEach(link => {
            if (link.getAttribute("href") === currentUrl) {
                link.classList.add("active"); // Agrega la clase 'active' al enlace actual
            }
        });
        
        document.getElementById("menu-toggle").addEventListener("click", function () {
            document.getElementById("sidebar").classList.toggle("show");
            document.getElementById("content").classList.toggle("shift");
        });


        function toggleDropdown() {
            document.getElementById("dropdownMenu").classList.toggle("active");
        }
        document.addEventListener("click", function(event) {
            var dropdown = document.getElementById("dropdownMenu");
            if (!event.target.closest(".profile")) {
                dropdown.classList.remove("active");
            }
        });
        
    });
    
    
   
    
                
                























      
        