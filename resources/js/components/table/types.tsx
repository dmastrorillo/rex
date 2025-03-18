// eslint-disable-next-line @typescript-eslint/no-explicit-any
export type GenericTableData = any;

export type ColumnInfo<T extends GenericTableData[]> = {
    [K in keyof T[number]]?: string;
};

export type GetKeyOf<T extends GenericTableData[]> = keyof T[number];
