from transformers import T5Tokenizer, T5ForConditionalGeneration

def paraphrase(question):
    tokenizer = T5Tokenizer.from_pretrained('t5-base')
    model = T5ForConditionalGeneration.from_pretrained('t5-base')

    inputs = tokenizer.encode("paraphrase: " + question, return_tensors="pt", max_length=512, truncation=True)
    outputs = model.generate(inputs, max_length=150, min_length=40, length_penalty=2.0, num_beams=4, early_stopping=True)
    paraphrased_question = tokenizer.decode(outputs[0])

    return paraphrased_question

user_question = input("Enter a question: ")
paraphrased_question = paraphrase(user_question)
print("Paraphrased question:", paraphrased_question)
