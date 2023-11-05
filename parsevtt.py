import pysrt

# Open the input WebVTT file
input_file = "toc1.vtt"
subtitle = pysrt.open(input_file)

# Define an array of special terms
special_terms = [
  "automata",
  "deterministic program",
  "delta",
  "cartesian product",
  "tollgate",
  "delta",
  "start state",
  "binary string",
  "reject state",
  "transition diagram",
  "table method",
  "transition function",
  "graphical representation",
  "graph theory",
  "automaton",
  "automata",
  "finite state automata",
  "transition system",
  "state transition",
  "memory",
  "language",
  "mathematically perceived",
  "string",
  "graph theory",
  "initial state",
  "start state",
  "accept state",
  "final state",
  "formal definition",
  "alphabet",
  "symbols",
  "function",
  "cartesian product",
  "transition function",
  "start state",
  "binary string",
  "state diagram",
  "state table",
  "graphical representation",
  "substring",
  "sequence number",
  "consecutive symbols",
  "accept string",
  "labeled transitions",
  "accept states",
  "reject states",
  "accept state circle",
  "previous example",
]


# Function to apply XML tags to special terms in the text
def apply_xml_tags(text, special_terms):
    for term in special_terms:
        text = text.replace(term, f"<special_term>{term}</special_term>")
    return text

# Process and apply XML tags to each subtitle
for subs in subtitle:
    subs.text = apply_xml_tags(subs.text, special_terms)

# Save the modified WebVTT file
output_file = "output.vtt"
subtitle.save(output_file, encoding="utf-8")

print(f"WebVTT file with XML tags applied to special terms saved as '{output_file}'")
