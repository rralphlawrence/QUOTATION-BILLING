

const imageDiv = document.querySelector('.P-modal-logo');
const img =document.querySelector('#profile-pic');
const file = document.querySelector('#file');
const uploadBtn = document.querySelector('#upload-button');

imageDiv.addEventListener('mouseenter', function(){
     uploadBtn.style.display = "block";
});
imageDiv.addEventListener('mouseleave', function(){
     uploadBtn.style.display = "none";
});



