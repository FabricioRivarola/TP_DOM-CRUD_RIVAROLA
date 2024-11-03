const modal = document.querySelector('.modal');
const buttons = document.querySelectorAll('#button1, #button2, #button3');
const closeButton = document.querySelector('.modal__content--close');
const cardsContainer = document.getElementById('cards-container');

const imageGroups = {
    group1: [
        'imageness/remeras-png-3-removebg-preview-removebg-preview.png',
        'imageness/OIP__1_-removebg-preview.png',
        'imageness/OIP-removebg-preview.png'
    ],
    group2: [
        'imageness/download-removebg-preview.png',
        'imageness/remeras-png-removebg-preview.png',
        'imageness/OIP__2_-removebg-preview.png'
    ],
    group3: [
        'imageness/OIP__3_-removebg-preview.png',
        'imageness/OIP__5_-removebg-preview.png',
        'imageness/OIP__4_-removebg-preview.png'
    ]
};


function showImages(group) {
    
    cardsContainer.innerHTML = '';
    
    imageGroups[group].forEach(src => {
        const label = document.createElement('label');
        label.className = 'card';
        
        const img = document.createElement('img');
        img.src = src;
        img.alt = 'Image';
        
        label.appendChild(img);
        cardsContainer.appendChild(label);
    });
}

buttons.forEach(button => {
    button.addEventListener('click', (event) => {
        const id = event.currentTarget.id;
        
        
        let group;
        if (id === 'button1') group = 'group1';
        if (id === 'button2') group = 'group2';
        if (id === 'button3') group = 'group3';

        showImages(group);
        
        modal.classList.remove('hidden');
        modal.classList.add('visible');
    });
});

closeButton.addEventListener('click', () => {
    modal.classList.remove('visible');
    modal.classList.add('hidden');
});
