import pandas as pd
import json

df = pd.read_excel('data_desa.xlsx', header=1)
karangendep_row = df[df['Nama Desa'].astype(str).str.contains('KARANGENDEP', case=False, na=False)]
print(karangendep_row.to_json(orient='records'))
