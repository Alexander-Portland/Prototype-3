//This function helps display a help note for the user
function helpNote(helpSection) {
    var hideVar = document.getElementById(helpSection);
    if (hideVar.style.display === "none") {
        hideVar.style.display = "block";
        document.getElementById("loginBox").style.height = "16em";
    } else {
        hideVar.style.display = "none";
        document.getElementById("loginBox").style.height = "10em";
    }
}

//This function helps minimise a section the user has selected by pressing a "display" button
function hideContent(contentID) {
    var hideVar = document.getElementById(contentID);
    if (hideVar.style.display === "none") {
        hideVar.style.display = "block";
    } else {
        hideVar.style.display = "none";
    }
}

//This function helps minimise a section that is identified by the contentID variable that is pa
function minimise(contentID){
    var hideVar = document.getElementById(contentID);
    hideVar.style.display = "none";
}

//This helps to 
function maximise(contentID){
    var hideVar = document.getElementById(contentID);
    hideVar.style.display = "block";
}

function successMesage(messageID){
    var messageVar = document.getElementById(messageID)
    messageVar.style.display = "block"
}

function addSend(addID,classTitle){
    maximise('classAdd');
    maximise('addClassTitle');
    minimise('classDelete');
    minimise('classSearch');
    minimise('deleteClassTitle');
    minimise('updateClassTitle');
    var addTopicID = document.getElementById(addID).textContent;
    var classTitleElement = document.getElementById("className");
    var addClassName = document.getElementById(classTitle).textContent;
    classTitleElement.innerHTML = addClassName;
    document.getElementsByName("classAddID")[0].value = addTopicID;
}

function updateSend(topicID, classTitle, postTitle, postDescription){
    maximise('classSearch');
    maximise('updateClassTitle');
    minimise('classDelete');
    minimise('classAdd');
    minimise('addClassTitle');
    minimise('deleteClassTitle');
    var classTitleElement = document.getElementById("classNameSearch");


    var updateClassName = document.getElementById(classTitle).textContent;
    var updatePostTitle = document.getElementById(postTitle).textContent;
    var updatePostDescription = document.getElementById(postDescription).textContent;

    classTitleElement.innerHTML = updateClassName;

    var updateID = document.getElementById(topicID).textContent;
    document.getElementsByName("classUpdateID")[0].value = updateID;
    var updatePostTitle = document.getElementById(postTitle).textContent;
    document.getElementsByName("postNameSearch")[0].value = updatePostTitle;
    var updatePostDescription = document.getElementById(postDescription).textContent;
    document.getElementsByName("classDescriptionSearch")[0].value = updatePostDescription;

    
}

function deleteSend(topicID,classTitle,postName){

    maximise('classDelete');
    maximise('deleteClassTitle');
    minimise('classAdd');
    minimise('classSearch');
    minimise('addClassTitle');
    minimise('updateClassTitle');
    var deleteIDElement = document.getElementsByName("postID");
    var classTitleElement = document.getElementById("classNameDelete");
    var postNameElement = document.getElementById("postNameDelete");

    var deleteID = document.getElementById(topicID).textContent;
    var deleteClassName = document.getElementById(classTitle).textContent;
    var deleteClassTitle = document.getElementById(postName).textContent;
    
    deleteIDElement.value = deleteID;
    classTitleElement.innerHTML = deleteClassName;
    postNameElement.innerHTML = deleteClassTitle;


    document.getElementsByName("classDeleteID")[0].value = deleteID;

}

function replySend(QuestionID,QuestionSenderJav,QuestionTitleJav,QuestionDescriptionJav){
    var questionIDElement = document.getElementsByName("classID");
    var fromElement = document.getElementById("labelFrom");
    var titleElement = document.getElementById("labelSubject");
    var descriptionElement = document.getElementById("labelQuestion");
    var sendID = document.getElementById(QuestionID).textContent;
    var sendName = document.getElementById(QuestionSenderJav).textContent;
    var sendSubject = document.getElementById(QuestionTitleJav).textContent;
    var sendQuestion = document.getElementById(QuestionDescriptionJav).textContent;
    questionIDElement.value = sendID;

    document.getElementsByName("classID")[0].value = sendID;
    fromElement.innerHTML = sendName;
    titleElement.innerHTML = sendSubject;
    descriptionElement.innerHTML = sendQuestion;
}
